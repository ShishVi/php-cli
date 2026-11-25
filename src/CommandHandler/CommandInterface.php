<?php

declare(strict_types=1);

namespace App\CommandHandler;

interface CommandInterface
{
    public function name(): string;

    public function execute(array $args): void;

}
