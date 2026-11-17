<?php

namespace App\UserRepository;

use App\User\User;

class UserRepository
{
    public function __construct(){}

    public function saveUser(User $user):void
    {
        try {
            $payload = json_encode( $user->getUser(), JSON_THROW_ON_ERROR);
            file_put_contents('data_base_user.json', $payload, FILE_APPEND);
            fwrite(STDOUT, "User saved successfully");
        } catch (\JsonException $e){
            echo "Error save: ". $e->getMessage();
        }
    }

    public static function getKeyID(string $file_bd):id
    {
        if(file_exists($file_bd)){
            try {
                $fileBD = file_get_contents($file_bd);
                $arrFile = json_decode($fileBD, true, 512, JSON_THROW_ON_ERROR);
                var_dump($arrFile);
                die();
            } catch (\JsonException $e){
                echo "Error read file: ". $e->getMessage();
            }

        }else{
            fwrite(STDERR, "File BD not found\n");
            exit(2);
        }
    }

}