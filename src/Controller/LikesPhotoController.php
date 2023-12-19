<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\LikesPhoto;
use App\Entity\Photo;
use App\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

class LikesPhotoController extends AbstractController
{
    // Ajouter un like à une photo
    #[Route('/api/photolikes', methods: ['POST'])]
    #[OA\Post(
        description: 'Ajouter un like ou un dislike à une photo',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'photoId', type: 'integer'),
                    new OA\Property(property: 'userId', type: 'integer'),
                    new OA\Property(property: 'likeType', type: 'boolean')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Like ajouté avec succès'
            ),
            new OA\Response(
                response: 404,
                description: 'Photo ou utilisateur non trouvé'
            )
        ]
    )]
    #[OA\Tag(name: 'LikesPhoto')]
    public function addLike(Request $request, ManagerRegistry $doctrine): JsonResponse
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

        $like = new LikesPhoto();
        $like->setUser($user);
        $like->setPhoto($photo);
        $like->setLikeType($data['likeType'] ?? true);

        if ($like->isLikeType()) {
            $photo->setLikesCount($photo->getLikesCount() + 1);
        } else {
            $photo->setDislikesCount($photo->getDislikesCount() + 1);
        }

        $entityManager->persist($like);
        $entityManager->flush();

        return new JsonResponse('Like ajouté avec succès.', Response::HTTP_CREATED);
    }




    // Supprimer un like d'une photo
    #[Route('/api/photolikes/{likeId}', methods: ['DELETE'])]
    #[OA\Delete(
        description: 'Supprimer un like d\'une photo',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Like supprimé avec succès'
            ),
            new OA\Response(
                response: 404,
                description: 'Like ou photo non trouvé'
            )
        ]
    )]
    #[OA\Tag(name: 'LikesPhoto')]
    public function removeLike(ManagerRegistry $doctrine, int $likeId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $like = $entityManager->getRepository(LikesPhoto::class)->find($likeId);

        $photo = $like->getPhoto();


        if (!$like) {
            return new JsonResponse("Le like n'existe pas.", Response::HTTP_NOT_FOUND);
        }
        if ($like->isLikeType()) {
            $photo->setLikesCount($photo->getLikesCount() - 1);
        } else {
            $photo->setDislikesCount($photo->getDislikesCount() - 1);
        }
        $entityManager->remove($like);
        $entityManager->flush();

        return new JsonResponse('Like supprimé avec succès.', Response::HTTP_OK);
    }




    // Récupérer tous les likes d'une photo
    #[Route('/api/photos/{photoId}/likes', methods: ['GET'])]
    #[OA\Get(
        description: 'Récupérer tous les likes et dislikes d\'une photo',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des likes pour la photo',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'likeId', type: 'integer'),
                            new OA\Property(property: 'likeType', type: 'boolean'),
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
    #[OA\Tag(name: 'LikesPhoto')]
    public function getLikesByPhoto(ManagerRegistry $doctrine, int $photoId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $photo = $entityManager->getRepository(Photo::class)->find($photoId);

        if (!$photo) {
            return new JsonResponse("La photo avec l'ID " . $photoId . " n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        $likes = $entityManager->getRepository(LikesPhoto::class)->findBy(['photo' => $photoId]);
        $response = [];

        foreach ($likes as $like) {
            $response[] = [
                'likeId' => $like->getId(),
                'likeType' => $like->isLikeType(),
                'userId' => $like->getUser()->getId(),
            ];
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
