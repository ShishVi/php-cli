<?php

namespace App\HandlerCommand;

use App\User\User;
use App\UserRepository\UserRepository;

class HandlerCommand
{

    public function __construct(private UserRepository $userRepository)
    {

    }

    public function checkCommand (array $argv):void
    {

        $command = $argv[1] ?? null;
        $name =$argv[2] ?? null;
        $lastName = $argv[3] ?? null;
        $email = $argv[4] ?? null;

         if($command === null){
             throw new \InvalidArgumentException('Usage: php index.php <command> [args...]');
         }

         match ($command) {
             'addUser' => $this->addUser($name, $lastName, $email),
         };
    }

    private function addUser(?string $name, ?string $lastName, ?string $email):void
    {
        if($name=== null || $lastName === null){
            throw new \InvalidArgumentException('Error command!');
        }
        $id = $this->userRepository->getNextID();
        if(!$id){
            throw new \InvalidArgumentException('Error get ID');
        }
        $this->userRepository->saveUser(new User($id, $name, $lastName, $email));

    }


}