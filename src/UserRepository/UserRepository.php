<?php

namespace App\UserRepository;

use App\User\User;

class UserRepository
{
    public function __construct(private User $user){}

    public function saveUser():void
    {
        try {
            $user = json_encode( $this->user->getUser(), JSON_THROW_ON_ERROR);
            file_put_contents('data_base_user.json', $user);
        } catch (\JsonException $e){
            echo "Error save: ". $e->getMessage();
        }

    }

}