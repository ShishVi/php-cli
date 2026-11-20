<?php

namespace App\Domain\User;

class User
{
    private ?int $id;
    private string $name;
    private string $lastName;
    private string $email;

    public function __construct(int $id, string $name, string $lastName, ?string $email='')
    {
        $this->id = $id;
        $this->name = $name;
        $this->lastName = $lastName;
        if(empty($email)){
            $this->email = User::generatedEmail();
        }

    }

    public function getUser():array
    {
        return [
            'ID' => $this->id,
            'NAME' => $this->name,
            'LAST_NAME' => $this->lastName,
            'EMAIL' => $this->email,
        ];
    }

    private static function generatedEmail():string
    {
        $domains = ['mail.ru', 'gmail.com', 'yandex.ru', 'outlook.com'];
        $randUserEmail = bin2hex(random_bytes(6));

        return $randUserEmail .'@'. $domains[array_rand($domains)];
    }

    public function withId(int $id): self
    {
        return new self($id, $this->name, $this->lastName, $this->email);
    }

}