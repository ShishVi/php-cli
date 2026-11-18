<?php

namespace App\UserRepository;

use App\User\User;

readonly class UserRepository
{

    public function __construct(private readonly string $fileBD){}

    public function saveUser(User $user):void
    {
        try {
            $userPayload = $user->getUser();
            $allUsers = $this->getAllUser();
            $allUsers[] = $userPayload;
            $payload = json_encode( $allUsers, JSON_THROW_ON_ERROR);
            file_put_contents($this->fileBD, $payload, LOCK_EX);
            fwrite(STDOUT, "User saved successfully");
            exit(1);
        } catch (\JsonException $e){
            echo "Error save: ". $e->getMessage();
        }
    }

    public function getNextID():int
    {
        $allUsers = $this->getAllUser();
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

    public function getAllUser():array
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
}