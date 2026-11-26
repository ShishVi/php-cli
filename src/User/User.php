<?php

declare(strict_types=1);

namespace App\User;

use LogicException;

class User
{
    public function __construct(private ?int $id, private readonly string $name, private readonly string $lastName, private readonly string $email = '')
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->lastName,
            'email' => $this->email,
        ];
    }

    public function getID(): int
    {
        if ($this->id === null) {
            throw new LogicException('У пользователя нет ID (ещё не сохранён)');
        }

        return (int)$this->id;
    }

    public function setID(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param array<string,mixed> $row
     */

    public static function fromArray(array $row): self
    {
        return new self(
            (int)$row['id'],
            (string)$row['name'],
            (string)$row['last_name'],
            (string)$row['email'],
        );
    }

}
