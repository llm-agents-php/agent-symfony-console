<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole\Integrations\Laravel;

use Illuminate\Contracts\Console\Kernel;
use LLM\Agents\Agent\SymfonyConsole\CommandManagerInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

final readonly class ArtisanCommandManager implements CommandManagerInterface
{
    public function __construct(
        private Kernel $application,
        private array $enabledNamespaces = [
            'make:',
            'db:',
            'migrate',
            'route:list',
        ],
    ) {}

    public function getCommandHelp(string $command): string
    {
        $output = new BufferedOutput();
        $this->application->call($command, ['--help' => true], $output);

        return $output->fetch();
    }

    public function getCommands(): array
    {
        $commands = $this->application->all();

        foreach ($this->enabledNamespaces as $namespace) {
            foreach ($commands as $name => $command) {
                if (!\str_starts_with($name, $namespace)) {
                    unset($commands[$name]);
                }
            }
        }

        return $this->application->all();
    }

    public function call(\Stringable|string $command, array $parameters = [], ?OutputInterface $output = null): int
    {
        return $this->application->call($command, $parameters, $output);
    }
}
