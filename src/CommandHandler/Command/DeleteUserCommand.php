<?php

declare(strict_types=1);

namespace App\CommandHandler\Command;

use App\CommandHandler\CommandInterface;
use App\UserRepository\UserRepository;
use RuntimeException;

final readonly class DeleteUserCommand implements CommandInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }
    public function name(): string
    {
        return 'deleteUser';
    }
    /**
     * @param string[] $args
     */
    public function execute(array $args): void
    {
        try {
            $id = (int)$args[0];
            $userID = $this->userRepository->deleteUser($id);
            if ($userID) {
                fwrite(STDOUT, 'User ' . $id . ' delete successfully');
            }
        } catch (RuntimeException $e) {
            fwrite(STDERR, 'Errors: ' . $e->getMessage());
        }
    }

}
