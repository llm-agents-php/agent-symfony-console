<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

interface CommandManagerInterface
{
    public function getCommandHelp(string $command): string;

    /**
     * @return array<non-empty-string, Command>
     */
    public function getCommands(): array;

    /**
     * Run a console command by name.
     *
     * @return int The command exit code
     */
    public function call(
        string|\Stringable $command,
        array $parameters = [],
        ?OutputInterface $output = null,
    ): int;
}
