<?php

namespace App\Infrastructure\Persistence\Json;

use App\Domain\User\User;
use App\Domain\User\UserRepositoryInterface;

readonly class JsonUserRepository implements UserRepositoryInterface
{

    public function __construct(private readonly string $fileBD){}

    public function saveUser(User $user):User
    {
        try {
            $nextID = $this->getNextID();

            $user = $user->withId($nextID);

            $userPayload = $user->getUser();

            $allUsers = $this->getArrAllUser();

            $allUsers[] = $userPayload;
            $payload = json_encode( $allUsers, JSON_THROW_ON_ERROR);
            file_put_contents($this->fileBD, $payload, LOCK_EX);
            fwrite(STDOUT, "User saved successfully");
            return $user;
        } catch (\JsonException $e){
            echo "Error save: ". $e->getMessage();
        }
    }

    private function getNextID():int
    {
        $allUsers = $this-> getArrAllUser();
        if(empty($allUsers)){
            return 1;
        }

        $maxId = 0;
        foreach($allUsers as $user){
            if(isset($user['ID']) && (int)$user['ID'] > $maxId){
                $maxId = (int)$user['ID'];
            }
        }
        return $maxId +1;
    }

    private function getArrAllUser():array
    {
        if(!file_exists($this->fileBD)|| filesize($this->fileBD) === 0){
            return [];
        }

        $content = file_get_contents($this->fileBD);

        if($content === false)
        {
            return [];
        }

        try{
            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            return $data ?? [];
        }catch (\JsonException $e){
            echo "Error read file: ". $e->getMessage();
            return [];
        }
    }

    public function allUsers():string
    {
        $loadAllUsers = $this->getArrAllUser();

        if(empty($loadAllUsers)){
            return 'No entries';
        }
        $result = implode('|', array_keys($loadAllUsers[0])). PHP_EOL;
        foreach($loadAllUsers as $user){
            $result .= implode('|', $user) . PHP_EOL;
        }
        return $result;
    }

    public function deleteUser(int $id):void
    {
        $loadAllUsers = $this->getArrAllUser();

        if(empty($loadAllUsers)){
            throw new \UnderflowException('No entries');
        }
        $userIdDelete = null;
        foreach($loadAllUsers as $key=>$user){
            if(isset($user['ID']) && $user['ID'] === $id){
                $userIdDelete = $key;
                unset($loadAllUsers[$key]);
            }
        }
        if($userIdDelete){
            try {
                $loadAllUsers = array_values($loadAllUsers);
                $payload = json_encode( $loadAllUsers, JSON_THROW_ON_ERROR);
                file_put_contents($this->fileBD, $payload, LOCK_EX);
                fwrite(STDOUT, "User " .$id ." delete successfully");
                exit(1);
            } catch (\JsonException $e){
                echo "Error save: ". $e->getMessage();
            }
        }else{
            fwrite(STDERR, "User ".$id." not found!");
            exit(2);
        }






    }


}