<?php

declare(strict_types=1);

namespace App\UserRepository;

use App\User\User;
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
        $allUsers = $this-> getArrAllUser();
        if (empty($allUsers)) {
            return 1;
        }
        $maxId = 0;
        foreach ($allUsers as $user) {
            if (isset($user['id']) && (int) $user['id'] > $maxId) {
                $maxId = (int) $user['id'];
            }
        }

        return $maxId + 1;
    }

    private function getArrAllUser(): array
    {
        if (!file_exists($this->fileDB) || filesize($this->fileDB) === 0) {
            return [];
        }

        $content = file_get_contents($this->fileDB);

        if ($content === false) {
            return [];
        }

        try {
            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            return $data ?? [];
        } catch (JsonException $e) {
            echo 'Error read file: ' . $e->getMessage();

            return [];
        }
    }

    public function allUsers(): array
    {
        return $this->getArrAllUser();
    }

    public function deleteUser(int $id): void
    {
        $loadAllUsers = $this->getArrAllUser();

        if (empty($loadAllUsers)) {
            throw new UnderflowException('No entries');
        }
        $userIdDelete = null;
        foreach ($loadAllUsers as $key => $user) {
            if (isset($user['id']) && $user['id'] === $id) {
                $userIdDelete = $key;
                unset($loadAllUsers[$key]);
            }
        }
        if ($userIdDelete) {
            try {
                $loadAllUsers = array_values($loadAllUsers);
                $payload = json_encode($loadAllUsers, JSON_THROW_ON_ERROR);
                file_put_contents($this->fileDB, $payload, LOCK_EX);
                fwrite(STDOUT, 'User ' . $id . ' delete successfully');
                exit(1);
            } catch (JsonException $e) {
                echo 'Error save: ' . $e->getMessage();
            }
        } else {
            fwrite(STDERR, 'User ' . $id . ' not found!');
            exit(2);
        }






    }


}
