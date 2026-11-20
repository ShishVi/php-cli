<?php

namespace App\Application;

use App\Domain\User\User;
use App\Infrastructure\Persistence\Json\JsonUserRepository;

class HandlerCommand
{

    public function __construct(private JsonUserRepository $userRepository)
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
             'getAllUsers' => $this->getAllUsers(),
             'deleteUser' => $this->deleteUser((int)$name),
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

    private function getAllUsers():void
    {
        $users = $this->userRepository->allUsers();
        fwrite(STDOUT, $users);
    }

    private function deleteUser(int $id):void
    {
        try{
            $this->userRepository->deleteUser($id);
        }catch (\UnderflowException $e){
            fwrite(STDERR, 'Errors: ' . $e->getMessage());
        }
    }


}