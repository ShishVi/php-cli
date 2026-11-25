<?php

declare(strict_types=1);

namespace App\CommandHandler\Command;

use App\CommandHandler\CommandInterface;
use App\User\User;
use App\UserRepository\UserRepository;
use Faker\Factory;
use InvalidArgumentException;
use JsonException;

final readonly class AddUserCommand implements CommandInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private Factory        $locale,
    ) {
    }
    public function name(): string
    {
        return 'addUser';
    }

    /**
     * @param string[] $args
     * @throws JsonException
     */
    public function execute(array $args): void
    {
        $name = $args[0] ?? null;
        $lastName = $args[1] ?? null;
        $email = $args[2] ?? null;

        if ($name === null || $lastName === null) {
            throw new InvalidArgumentException('Usage: addUser <name> <lastName> <email(optional)>');
        }

        if ($email === null) {
            $email = $this->locale::create('ru_RU')->email();
        }

        $savedUser = $this->userRepository->saveUser(new User(null, $name, $lastName, $email));

        fwrite(STDOUT, "User added: ID={$savedUser->getID()}");
    }

}
