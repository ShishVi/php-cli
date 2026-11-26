<?php

declare(strict_types=1);

namespace App;

use App\CommandHandler\Command\AddUserCommand;
use App\CommandHandler\Command\DeleteUserCommand;
use App\CommandHandler\Command\GetAllUsersCommand;
use App\CommandHandler\CommandHandler;
use App\UserRepository\UserRepository;
use Faker\Factory;

include_once __DIR__ . '/vendor/autoload.php';

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, 'Usage console!');
    exit(2);
}

try {
    $userRepository = new UserRepository('data/data_base_user.json');
    $locale = new Factory();
    $commands =[
        new AddUserCommand($userRepository, $locale),
        new GetAllUsersCommand($userRepository),
        new DeleteUserCommand($userRepository)
    ];
    $handler = new CommandHandler($commands);
    $handler->checkCommand($argv);
} catch (\InvalidArgumentException $e) {
    fwrite(STDERR, "Error: {$e->getMessage()}\n");
    exit(2);
}
