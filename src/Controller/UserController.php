<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use OpenApi\Attributes as OA;

use App\Service\JsonConverter;
use App\Entity\User;

class UserController extends AbstractController
{

    private $jsonConverter;
    private $passwordHasher;

    public function __construct(JsonConverter $jsonConverter, UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->jsonConverter = $jsonConverter;
    }

    #[Route('/api/login', methods: ['POST'])]
    #[Security(name: null)]
    #[OA\Post(description: 'Connexion à l\'API')]
    #[OA\Response(
        response: 200,
        description: 'Un token'
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'username', type: 'string', default: 'admin'),
                new OA\Property(property: 'password', type: 'string', default: 'password')
            ]
        )
    )]
    #[OA\Tag(name: 'utilisateurs')]
    public function logUser(ManagerRegistry $doctrine, JWTTokenManagerInterface $JWTManager)
    {
        $request = Request::createFromGlobals();
        $data = json_decode($request->getContent(), true);

        if (!is_array($data) || $data == null || empty($data['username']) || empty($data['password'])) {
            return new Response('Les champs ne doivent pas être vide', 401);
        }

        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $data['username']]);

        if (!$user) {
            return new Response('Username invalide');
        }
        if (!$this->passwordHasher->isPasswordValid($user, $data['password'])) {
            return new Response('Password invalide');
        }

        $token = $JWTManager->create($user);
        return new JsonResponse(['token' => $token]);
    }

    #[Route('/api/myself', methods: ['GET'])]
    #[OA\Get(description: 'Retourne l\'utilisateur authentifié')]
    #[OA\Response(
        response: 200,
        description: 'L\'utilisateur correspondant au token passé dans le header',
        content: new OA\JsonContent(ref: new Model(type: User::class))
    )]
    #[OA\Tag(name: 'utilisateurs')]
    public function getUtilisateur(JWTEncoderInterface $jwtEncoder, Request $request)
    {
        $tokenString = str_replace('Bearer ', '', $request->headers->get('Authorization'));

        $user = $jwtEncoder->decode($tokenString);

        return new Response($this->jsonConverter->encodeToJson($user));
    }






    #[Route('/api/createUser', methods: ['POST'])]
    #[OA\Post(description: 'Crée un nouvel utilisateur')]
    #[OA\Response(
        response: 200,
        description: 'La nouvelle utilisateur créée',
        content: new OA\JsonContent(ref: new Model(type: User::class))
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'username', type: 'string'),
                new OA\Property(property: 'password', type: 'string'),
                new OA\Property(property: 'avatar', type: 'string'),
                new OA\Property(property: 'description', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'utilisateurs')]
    public function createUser(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setUsername($data['username']);
        $user->setDescription($data['description']);
        $user->setBan(0);
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);

        if (isset($data['avatar'])) {
            if ($data['avatar'] != "default.png") {
                // Gestion de l'image en Base64
                $imageBase64 = $data['avatar'];
                $image = base64_decode($imageBase64);
                $imageName = uniqid() . '.png';
                file_put_contents(__DIR__ . '/../../public/images/avatar/' . $imageName, $image);

                // Enregistrement du nom de fichier dans l'utilisateur
                $user->setAvatar($imageName);
            } else {
                $user->setAvatar('default.png');
            }

            $entityManager->persist($user);
            $entityManager->flush();

            // Modification ici: retourner une réponse JSON structurée
            return new JsonResponse(['success' => true, 'message' => 'Utilisateur créé avec succès'], Response::HTTP_CREATED);
        }
        return new Response("L'image n'est pas définis.");
    }






    // erreur inconu voir la doc


    #[Route('/api/users', methods: ['PUT'])]
    #[OA\Put(description: 'Update un nouvel utilisateur')]
    #[OA\Response(
        response: 200,
        description: "L'utilisateur a ete modifier",
        content: new OA\JsonContent(ref: new Model(type: User::class))
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'number'),
                new OA\Property(property: 'password', type: 'string'),
                new OA\Property(property: 'avatar', type: 'string'),
                new OA\Property(property: 'description', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'utilisateurs')]
    public function updateUser(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $request = Request::createFromGlobals();
        $data = json_decode($request->getContent(), true);
        $user = $doctrine->getRepository(User::class)->find($data['id']);
        if (!$user) {
            throw $this->createNotFoundException(
                "Pas d'utilisateur"
            );
        }
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        $user->setAvatar($data['avatar']);
        $user->setDescription($data['description']);
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response($this->jsonConverter->encodeToJson($user));
    }


    //pas d'erreur mais ne trouve pas l'user
    #[Route('/api/users/{username}', methods: ['GET'])]
    #[OA\Get(description: 'Retourne le profil de l\'utilisateur rechercher')]
    #[OA\Response(
        response: 200,
        description: 'Le profil d\'un user',
        content: new OA\JsonContent(ref: new Model(type: User::class))
    )]
    #[OA\Parameter(
        name: 'username',
        in: 'path',
        schema: new OA\Schema(type: 'string'),
        required: true,
        description: 'Le nom d\'un utilisateur'
    )]
    #[OA\Tag(name: 'utilisateurs')]
    public function getUserByName(ManagerRegistry $doctrine, $username)
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(user::class)->findOneBy(['username' => $username]);
        return new Response($this->jsonConverter->encodeToJson($user));
    }

    #[Route('/api/getIdByUsername/{username}', methods: ['GET'])]
    #[OA\Get(description: "Retourne l'utilisateur authentifié par son username")]
    #[OA\Response(
        response: 200,
        description: "Récupère l'id d'un utilisateur à partir de son username",
        content: new OA\JsonContent(ref: new Model(type: User::class))
    )]
    #[OA\Tag(name: 'utilisateurs')]
    public function getIdByUsername(ManagerRegistry $doctrine, $username)
    {
        $entityManager = $doctrine->getManager();

        // Vérifiez si le paramètre username est présent dans l'URL
        if (!$username) {
            return new JsonResponse(['error' => "Le paramètre 'username' est nécessaire dans l'URL"], Response::HTTP_NOT_FOUND);
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        // Vérifiez si l'utilisateur existe
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Retournez uniquement l'ID de l'utilisateur
        $responseData = ['id' => $user->getId()];

        return new JsonResponse($responseData);
    }
}
