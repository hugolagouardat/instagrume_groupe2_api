<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\LikesCommentaire;
use App\Entity\Commentaire;
use App\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

class LikesCommentaireController extends AbstractController
{
    // Ajouter un like ou dislike à un commentaire
    #[Route('/api/commentlikes', methods: ['POST'])]
    #[OA\Post(
        description: 'Ajouter un like ou un dislike à un commentaire',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'commentaireId', type: 'integer'),
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
                description: 'Commentaire ou utilisateur non trouvé'
            )
        ]
    )]
    #[OA\Tag(name: 'LikesCommentaire')]
    public function addLike(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $commentaireId = $data['commentaireId'];

        $entityManager = $doctrine->getManager();
        $commentaire = $entityManager->getRepository(Commentaire::class)->find($commentaireId);

        if (!$commentaire) {
            return new JsonResponse("Le commentaire avec l'ID " . $commentaireId . " n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        $user = $entityManager->getRepository(User::class)->find($data['userId']);
        if (!$user) {
            return new JsonResponse("L'utilisateur n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        $like = new LikesCommentaire();
        $like->setUser($user);
        $like->setCommentaire($commentaire);
        $like->setLikeType($data['likeType'] ?? true);

        if ($like->isLikeType()) {
            $commentaire->setLikesCount($commentaire->getLikesCount() + 1);
        } else {
            $commentaire->setDislikesCount($commentaire->getDislikesCount() + 1);
        }
        $entityManager->persist($like);
        $entityManager->flush();

        return new JsonResponse('Like ajouté avec succès.', Response::HTTP_CREATED);
    }

    // Supprimer un like ou dislike d'un commentaire
    #[Route('/api/commentlikes/{likeId}', methods: ['DELETE'])]
    #[OA\Delete(
        description: 'Supprimer un like d\'un commentaire',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Like supprimé avec succès'
            ),
            new OA\Response(
                response: 404,
                description: 'Like ou commentaire non trouvé'
            )
        ]
    )]
    #[OA\Tag(name: 'LikesCommentaire')]
    public function removeLike(ManagerRegistry $doctrine, int $likeId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $like = $entityManager->getRepository(LikesCommentaire::class)->find($likeId);

        if (!$like) {
            return new JsonResponse("Le like n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        $commentaire = $like->getCommentaire();

        if ($like->isLikeType()) {
            $commentaire->setLikesCount($commentaire->getLikesCount() - 1);
        } else {
            $commentaire->setDislikesCount($commentaire->getDislikesCount() - 1);
        }

        $entityManager->remove($like);
        $entityManager->flush();

        return new JsonResponse('Like supprimé avec succès.', Response::HTTP_OK);
    }

    // Récupérer tous les likes et dislikes d'un commentaire
    #[Route('/api/commentaires/{commentaireId}/likes', methods: ['GET'])]
    #[OA\Get(
        description: 'Récupérer tous les likes et dislikes d\'un commentaire',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des likes pour le commentaire',
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
                description: 'Commentaire non trouvé'
            )
        ]
    )]
    #[OA\Tag(name: 'LikesCommentaire')]
    public function getLikesByCommentaire(ManagerRegistry $doctrine, int $commentaireId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $commentaire = $entityManager->getRepository(Commentaire::class)->find($commentaireId);

        if (!$commentaire) {
            return new JsonResponse("Le commentaire avec l'ID " . $commentaireId . " n'existe pas.", Response::HTTP_NOT_FOUND);
        }

        $likes = $entityManager->getRepository(LikesCommentaire::class)->findBy(['commentaire' => $commentaireId]);
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

