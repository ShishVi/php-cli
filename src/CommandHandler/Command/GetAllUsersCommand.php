<?php

declare(strict_types=1);

namespace App\CommandHandler\Command;

use App\CommandHandler\CommandInterface;
use App\UserRepository\UserRepository;
use InvalidArgumentException;

final readonly class GetAllUsersCommand implements CommandInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }
    public function name(): string
    {
        return 'getAllUsers';
    }
    public function execute(array $args): void
    {
        $users = $this->userRepository->allUsers();
        if ($users === []) {
            throw new InvalidArgumentException('No users found!\n');
        }
        $headers = array_keys($users[0]);
        fwrite(STDOUT, implode('|', $headers) . PHP_EOL);

        foreach ($users as $user) {
            $str = array_map(static fn ($v) => (string) $v, array_values($user));
            fwrite(STDOUT, implode('|', $str) . PHP_EOL);
        }
    }

}
