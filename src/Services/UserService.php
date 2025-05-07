<?php
namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function findUserByUsername(string $username): ?User
    {
        return $this->userRepository->findByUserName($username);
    }

    public function createNewUser(string $username, string $plainPassword): User
    {
        $user = new User();
        $user->setUserName($username);
        $user->setPassword($plainPassword); // Hash if needed

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
