<?php
namespace App\UseCases\Interfaces\Services;

use App\Entity\User;

interface IUserService
{
    public function findUserByUsername(string $username): ?User;

    public function createUser(string $username, string $plainPassword): User;

    public function findByUserId(string $userId): ?User;
}
