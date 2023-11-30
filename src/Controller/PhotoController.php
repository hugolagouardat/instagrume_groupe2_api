<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

use OpenApi\Attributes as OA;

use App\Service\JsonConverter;
use App\Entity\User;
use App\Entity\Photo;
use DateTime;

class PhotoController extends AbstractController
{

    //Récupérer toutes les photos
    #[Route('/api/photos', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Post(description: 'Récupération de toutes les photos')]
    #[OA\Response(
        response: 200,
        description: 'Toutes les photos'
    )]
    #[OA\Tag(name: 'photos')]
    public function getAllPhotos(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $photos = $entityManager->getRepository(Photo::class)->findAll();

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $jsonObject = $serializer->serialize($photos, 'json', [
            'circular_reference_handler' => function ($photos) {
                return $photos->getId();
            }
        ]);

        return new JsonResponse($jsonObject, JsonResponse::HTTP_OK, [], true);
    }

    //Récupérer une photo par son id
    #[Route('/api/photos/{id}', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Post(description: 'Récupérer une photo par son id')]
    #[OA\Response(
        response: 200,
        description: 'La photo est récupérée'
    )]
    #[OA\Tag(name: 'photos')]
    public function getPhotoById(ManagerRegistry $doctrine, $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $photo = $entityManager->getRepository(Photo::class)->find($id);

        if (!$photo) {
            return new JsonResponse("La photo avec l'ID " . $id . " n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $jsonObject = $serializer->serialize($photo, 'json', [
            'circular_reference_handler' => function ($photo) {
                return $photo->getId();
            }
        ]);

        return new JsonResponse($jsonObject, JsonResponse::HTTP_OK, [], true);
    }

    //Supprimer une photo
    #[Route('/api/photos/{id}', methods: ['DELETE'])]
    #[Security(name: null)]
    #[OA\Post(description: 'Supprimer une photo avec son id')]
    #[OA\Response(
        response: 200,
        description: 'La photo à été supprimé'
    )]
    #[OA\Tag(name: 'photos')]
    public function deletePhoto($id, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $photo = $entityManager->getRepository(Photo::class)->find($id);

        if (!$photo) {
            return new JsonResponse("La photo avec l'ID " . $id . " n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($photo);
        $entityManager->flush();

        return new JsonResponse("La photo avec l'ID " . $id . " a été supprimée.", Response::HTTP_OK);
    }

    //Modifier une photo
    #[Route('/api/photos', methods: ['PUT'])]
    #[Security(name: null)]
    #[OA\Post(description: 'Modifier une photo avec son id')]
    #[OA\Response(
        response: 200,
        description: 'La photo à été modifié'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'int'),
                new OA\Property(property: 'description', type: 'string'),

            ]
        )
    )]
    #[OA\Tag(name: 'photos')]
    public function updatePhoto(Request $request, ManagerRegistry $doctrine)
    {
        $data = json_decode($request->getContent(), true);

        // Vérifiez si les données contiennent l'ID et la description
        if (!isset($data['id']) || !isset($data['description'])) {
            return new JsonResponse('Les champs "id" et "description" sont obligatoires.', Response::HTTP_BAD_REQUEST);
        }

        $id = $data['id'];
        $entityManager = $doctrine->getManager();
        $photo = $entityManager->getRepository(Photo::class)->find($id);

        // Vérifiez si la photo existe
        if (!$photo) {
            return new JsonResponse("La photo avec l'ID " . $id . " n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        // Mettre à jour la description
        $photo->setDescription($data['description']);
        $entityManager->flush();

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $jsonObject = $serializer->serialize($photo, 'json', [
            'circular_reference_handler' => function ($photo) {
                return $photo->getId();
            }
        ]);

        return new JsonResponse($jsonObject, JsonResponse::HTTP_OK, [], true);
    }

    //Ajouter une nouvelle photo
    #[Route('/api/photos', methods: ['POST'])]
    public function addPhoto(Request $request, ManagerRegistry $doctrine)
    {
        $data = json_decode($request->getContent(), true);

        // Vérifiez si les données sont valides
        if (!isset($data['image']) || !isset($data['description'])) {
            return new JsonResponse('Les champs "image" et "description" sont obligatoire.', Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $doctrine->getManager();

        // Créez une nouvelle instance de l'entité Photo
        $photo = new Photo();
        $photo->setImage($data['image']);
        $photo->setDescription($data['description']);

        $date = new DateTime();
        $photo->setDatePoste($date);

        if (isset($data['is_locked'])) {
            $photo->setIsLocked($data['is_locked']);
        } else {
            $photo->setIsLocked(false);
        }

        $user = $entityManager->getRepository(User::class)->find($data['user_id']);

        if (!$user) {
            return new JsonResponse("L'utilisateur n'existe pas.", Response::HTTP_NOT_FOUND);
        }
        $photo->setUser($user);

        $entityManager->persist($photo);
        $entityManager->flush();

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $jsonObject = $serializer->serialize($photo, 'json', [
            'circular_reference_handler' => function ($photo) {
                return $photo->getId();
            }
        ]);

        return new Response($jsonObject, Response::HTTP_CREATED);
    }
}
