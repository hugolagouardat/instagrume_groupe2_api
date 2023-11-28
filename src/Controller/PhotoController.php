<?php

namespace App\Controller;

use App\Entity\Photo;

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

class PhotoController extends AbstractController {

    #[Route('/api/photos', methods: ['GET'])]
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

    /*#[Route('/api/photos', methods: ['GET'])]
    public function getAllPhotos(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $photos = $entityManager->getRepository(Photo::class)->findAll();

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $jsonObject = $serializer->serialize($photos, 'json', ['groups' => 'photo']);

        return new JsonResponse($jsonObject, JsonResponse::HTTP_OK, [], true);
    }*/
}

    
