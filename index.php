<?php
declare(strict_types=1);
namespace App;
include_once __DIR__ . '/vendor/autoload.php';

if(!empty($argv)){
    $name = htmlspecialchars($argv[1] ?? '');
    $lastName = htmlspecialchars($argv[2] ?? '');
    $email = htmlspecialchars($argv[3] ?? '');
    $user = new User\User(1,$name, $lastName, $email);
    var_dump($user);
    $repository = new UserRepository\UserRepository($user);
    $repository->saveUser($user);
    var_dump($user);
}




