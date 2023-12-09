<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Commentaire;
use App\Entity\User;
use App\Entity\Photo;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use OpenApi\Attributes as OA;

class CommentaireController extends AbstractController
{
    // Ajouter un commentaire à une photo
    #[Route('/api/commentaires', methods: ['POST'])]
    #[OA\Post(
        description: 'Ajouter un commentaire à une photo',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'photoId', type: 'integer'),
                    new OA\Property(property: 'userId', type: 'integer'),
                    new OA\Property(property: 'description', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Commentaire ajouté avec succès'
            ),
            new OA\Response(
                response: 404,
                description: 'Photo ou utilisateur non trouvé'
            )
        ]
    )]
    #[OA\Tag(name: 'Commentaire')]
    public function addCommentaire(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $photoId = $data['photoId'];
        $entityManager = $doctrine->getManager();
        $photo = $entityManager->getRepository(Photo::class)->find($photoId);

        if (!$photo) {
            return new JsonResponse("La photo avec l'ID " . $photoId . " n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        $user = $entityManager->getRepository(User::class)->find($data['userId']);
        if (!$user) {
            return new JsonResponse("L'utilisateur n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        $commentaire = new Commentaire();
        $commentaire->setUser($user);
        $commentaire->setPhoto($photo);
        $commentaire->setDislikesCount(0);
        $commentaire->setLikesCount(0);
        $commentaire->setDescription($data['description']);
        $commentaire->setDateCommentaire(new \DateTime());

        $entityManager->persist($commentaire);
        $entityManager->flush();

        return new JsonResponse('Commentaire ajouté avec succès.', Response::HTTP_CREATED);
    }

    // Modifier un commentaire
    #[Route('/api/commentaires', methods: ['PUT'])]
    #[OA\Put(
        description: 'Modifier un commentaire',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'commentaireId', type: 'integer'),
                    new OA\Property(property: 'description', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Commentaire modifié avec succès'
            ),
            new OA\Response(
                response: 404,
                description: 'Commentaire non trouvé'
            )
        ]
    )]
    #[OA\Tag(name: 'Commentaire')]
    public function updateCommentaire(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $commentaireId = $data['commentaireId'];

        $entityManager = $doctrine->getManager();
        $commentaire = $entityManager->getRepository(Commentaire::class)->find($commentaireId);

        if (!$commentaire) {
            return new JsonResponse("Le commentaire avec l'ID " . $commentaireId . " n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        $commentaire->setDescription($data['description']);
        $entityManager->flush();

        return new JsonResponse('Commentaire modifié avec succès.', Response::HTTP_OK);
    }


    // Supprimer un commentaire
    #[Route('/api/commentaires/{commentaireId}', methods: ['DELETE'])]
    #[OA\Delete(
        description: 'Supprimer un commentaire',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Commentaire supprimé avec succès'
            ),
            new OA\Response(
                response: 404,
                description: 'Commentaire non trouvé'
            )
        ]
    )]
    #[OA\Tag(name: 'Commentaire')]
    public function deleteCommentaire(ManagerRegistry $doctrine, int $commentaireId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $commentaire = $entityManager->getRepository(Commentaire::class)->find($commentaireId);

        if (!$commentaire) {
            return new JsonResponse("Le commentaire avec l'ID " . $commentaireId . " n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($commentaire);
        $entityManager->flush();

        return new JsonResponse('Commentaire supprimé avec succès.', Response::HTTP_OK);
    }

    // Récupérer tous les commentaires d'une photo
    #[Route('/api/photos/{photoId}/commentaires', methods: ['GET'])]
    #[OA\Get(
        description: 'Récupérer tous les commentaires d\'une photo',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des commentaires pour la photo',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'commentaireId', type: 'integer'),
                            new OA\Property(property: 'description', type: 'string'),
                            new OA\Property(property: 'userId', type: 'integer')
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Photo non trouvée'
            )
        ]
    )]
    #[OA\Tag(name: 'Commentaire')]
    public function getCommentairesByPhoto(ManagerRegistry $doctrine, int $photoId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $commentaires = $entityManager->getRepository(Commentaire::class)->findBy(['photo' => $photoId]);
        $response = [];

        foreach ($commentaires as $commentaire) {
            $response[] = [
                'commentaireId' => $commentaire->getId(),
                'description' => $commentaire->getDescription(),
                'userId' => $commentaire->getUser()->getId(),
            ];
        }

        if (empty($response)) {
            return new JsonResponse("Aucun commentaire trouvé pour la photo avec l'ID " . $photoId, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

    #[Route('/api/commentaires', methods: ['GET'])]
    #[OA\Get(
        description: 'Récupérer tous les commentaires',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste de tous les commentaires',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'commentaireId', type: 'integer'),
                            new OA\Property(property: 'description', type: 'string'),
                            new OA\Property(property: 'userId', type: 'integer'),
                            new OA\Property(property: 'photoId', type: 'integer')
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Aucun commentaire trouvé'
            )
        ]
    )]
    #[OA\Tag(name: 'Commentaire')]
    public function getAllCommentaires(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $commentaires = $entityManager->getRepository(Commentaire::class)->findAll();

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $jsonObject = $serializer->serialize($commentaires, 'json', [
            'circular_reference_handler' => function ($commentaires) {
                return $commentaires->getId();
            }
        ]);

        return new JsonResponse($jsonObject, JsonResponse::HTTP_OK, [], true);
    }

}
