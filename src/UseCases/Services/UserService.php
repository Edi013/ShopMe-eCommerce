<?php
namespace App\UseCases\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use App\UseCases\Interfaces\Services\IUserService;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class UserService implements IUserService
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

    public function isUserAdmin(string $username): bool
    {
        return $this->userRepository->isUserAdmin($username);
    }

    public function createUser(string $username, string $plainPassword): User
    {
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        $user = new User();
        $user->setUserName($username);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function findByUserId(string $userId): ?User
    {
        return $this->userRepository->find(Uuid::fromString($userId));
    }

}
