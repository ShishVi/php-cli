<?php
declare(strict_types=1);

namespace App\Domain\User;

interface UserRepositoryInterface
{

    public function saveUser(User $user): User;
    public function allUsers():string;
    public function deleteUser(int $id): void;
}