<?php

declare(strict_types=1);

namespace App\User;

use Faker\Generator;

class User
{
    public function __construct(private ?int $id, private string $name, private string $lastName, private Generator $faker, private ?string $email = '')
    {
        if (empty($email)) {
            $this->email = $this->faker->email();
        }
    }

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
        return (int)$this->id;
    }

     public function setID(int $id): void
    {
        $this->id = $id;
    }

}
