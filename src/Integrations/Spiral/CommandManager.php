<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole\Integrations\Spiral;

use LLM\Agents\Agent\SymfonyConsole\CommandManagerInterface;
use Psr\Container\ContainerInterface;
use Spiral\Console\Console;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

final readonly class CommandManager implements CommandManagerInterface
{
    public function __construct(
        private ContainerInterface $app,
        private array $enabledNamespaces = [
            'create:',
            'cycle:',
            'migrate',
            'route:list',
        ],
    ) {}

    private function getConsole(): Console
    {
        return $this->app->get(Console::class);
    }

    public function getCommandHelp(string $command): string
    {
        $output = new BufferedOutput();
        $this->call($command, ['--help' => true], $output);

        return $output->fetch();
    }

    public function getCommands(): array
    {
        $commands = $this->getConsole()->getApplication()->all();

        $availableCommands = [];
        foreach ($this->enabledNamespaces as $namespace) {
            foreach ($commands as $name => $command) {
                if (\str_starts_with($name, $namespace)) {
                    $availableCommands[$name] = $command;
                }
            }
        }

        return $availableCommands;
    }

    public function call(\Stringable|string $command, array $parameters = [], ?OutputInterface $output = null): int
    {
        return $this->getConsole()->run((string) $command, $parameters, $output)->getCode();
    }
}