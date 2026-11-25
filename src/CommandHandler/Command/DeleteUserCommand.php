<?php

declare(strict_types=1);

namespace App\CommandHandler\Command;

use App\CommandHandler\CommandInterface;
use App\UserRepository\UserRepository;
use UnderflowException;

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
            $this->userRepository->deleteUser($id);
        } catch (UnderflowException $e) {
            fwrite(STDERR, 'Errors: ' . $e->getMessage());
        }
    }

}
