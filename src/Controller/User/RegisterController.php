<?php
// src/Controller/AuthController.php
namespace App\Controller\User;

use App\Dto\RegisterDto;
use App\Entity\User;
use App\Enum\BusinessRoles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload(
        )] RegisterDto $dto,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
    ): JsonResponse {
        $user = new User();
        $user->setFirstname($dto->firstname);
        $user->setLastname($dto->lastname);
        $user->setEmail($dto->email);
        $user->setRoles(['ROLE_USER']);
        $user->setBusinessRole(BusinessRoles::Resident);
        $user->setPassword($hasher->hashPassword($user, $dto->password));

        $em->persist($user);
        $em->flush();

        return $this->json([
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'role' => $user->getBusinessRole(),
        ], 201);
    }
}

