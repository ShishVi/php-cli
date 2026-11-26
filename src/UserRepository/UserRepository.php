<?php

declare(strict_types=1);

namespace App\UserRepository;

use App\User\User;
use InvalidArgumentException;
use JsonException;
use RuntimeException;
use UnderflowException;

readonly class UserRepository
{
    public function __construct(private readonly string $fileDB)
    {
    }

    public function saveUser(User $user): User
    {
        $id = $this->getNextID();
        if (!$id) {
            throw new InvalidArgumentException('Error get ID');
        }
        $user->setID($id);
        $userPayload = $user->toArray();
        $allUsers = $this->getArrAllUser();
        $allUsers[] = $userPayload;
        $payload = json_encode($allUsers, JSON_THROW_ON_ERROR);
        $writed = file_put_contents($this->fileDB, $payload, LOCK_EX);

        if ($writed === false) {
            throw new RuntimeException('Unable to write to file DB');
        }

        return $user;
    }

    public function getNextID(): int
    {
        $rows = $this-> getArrAllUser();
        if (empty($rows)) {
            return 1;
        }
        $users = [];
        foreach ($rows as $row) {
            $user = User::fromArray($row);
            $users[$user->getID()] = $user;
        }


        return max(array_keys($users)) + 1;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function getArrAllUser(): array
    {
        if (!is_file($this->fileDB)) {
            return [];
        }

        $content = file_get_contents($this->fileDB);

        if ($content === false) {
            throw new RuntimeException("Cannot read {$this->fileDB}");
        }

        try {
            return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException("Cannot parse {$this->fileDB}: {$e->getMessage()}");
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function allUsers(): array
    {
        return $this->getArrAllUser();
    }

    public function deleteUser(int $id): int
    {
        $loadAllUsers = $this->getArrAllUser();

        if (empty($loadAllUsers)) {
            throw new RuntimeException('No entries');
        }
        $userIdDelete = null;

        foreach ($loadAllUsers as $key => $user) {
            if (isset($user['id']) && $user['id'] === $id) {
                $userIdDelete = $user['id'];
                unset($loadAllUsers[$key]);
            }
        }
        if ($userIdDelete !== null) {
            try {
                $loadAllUsers = array_values($loadAllUsers);
                $payload = json_encode($loadAllUsers, JSON_THROW_ON_ERROR);
                file_put_contents($this->fileDB, $payload, LOCK_EX);
                return (int)$userIdDelete;
            } catch (JsonException $e) {

                throw new RuntimeException("Error delete User {$userIdDelete}: {$e->getMessage()}");
            }
        } else {

            throw new RuntimeException("User  {$id} not found!");

        }
    }
}
