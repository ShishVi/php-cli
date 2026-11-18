<?php
declare(strict_types=1);
namespace App;
use App\HandlerCommand\HandlerCommand;

include_once __DIR__ . '/vendor/autoload.php';

if(PHP_SAPI !== 'cli'){
    fwrite(STDERR, 'Usage console!');
    exit(2);
}

try{
    $handler = new HandlerCommand(new UserRepository\userRepository('data/data_base_user.json'));
    $handler->checkCommand($argv);
}catch(\InvalidArgumentException $e){
    fwrite(STDERR, "Error: {$e->getMessage()}\n");
    exit(2);
}




