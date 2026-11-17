<?php

namespace App\User;

class User
{

    private string $name;
    private string $lastName;
    private string $email;

    public function __construct(string $name, string $lastName, ?string $email='')
    {

        $this->name = $name;
        $this->lastName = $lastName;
        if(empty($email)){
            $this->email = User::generatedEmail();
        }

    }

    public function getUser():array
    {
        return [
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


}