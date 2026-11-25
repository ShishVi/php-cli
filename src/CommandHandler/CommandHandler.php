<?php

declare(strict_types=1);

namespace App\CommandHandler;

use InvalidArgumentException;

final class CommandHandler
{
    private array $commands = [];
    public function __construct(array $commands)
    {
        foreach ($commands as $command) {
            $this->commands[$command->name()] = $command;
        }
    }

    public function checkCommand(array $argv): void
    {

        $command = $argv[1] ?? null;

        if ($command === null) {
            throw new InvalidArgumentException('Usage: php index.php <command> [args...]');
        }
        if (!isset($this->commands[$command])) {
            throw new InvalidArgumentException(
                "Unknown command: {$command}",
            );
        }
        $this->commands[$command]->execute(\array_slice($argv, 2));
    }
}
