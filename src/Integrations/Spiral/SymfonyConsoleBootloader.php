<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole\Integrations\Spiral;

use LLM\Agents\Agent\AgentRegistryInterface;
use LLM\Agents\Agent\SymfonyConsole\CommandManagerInterface;
use LLM\Agents\Agent\SymfonyConsole\ExecuteCommandTool;
use LLM\Agents\Agent\SymfonyConsole\GetCommandDetailsTool;
use LLM\Agents\Agent\SymfonyConsole\GetCommandsListTool;
use LLM\Agents\Agent\SymfonyConsole\ReadFileTool;
use LLM\Agents\Agent\SymfonyConsole\SymfonyConsoleAgentFactory;
use LLM\Agents\Agent\SymfonyConsole\WriteFileTool;
use LLM\Agents\Tool\ToolRegistryInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;

final class SymfonyConsoleBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            SymfonyConsoleAgentFactory::class => static fn() => new SymfonyConsoleAgentFactory('Spiral'),
            CommandManagerInterface::class => CommandManager::class,
        ];
    }

    public function boot(
        AgentRegistryInterface $agents,
        ToolRegistryInterface $tools,
        SymfonyConsoleAgentFactory $agentFactory,
        CommandManagerInterface $commandManager,
        DirectoriesInterface $dirs,
    ): void {
        $agents->register(
            $agentFactory->create(),
        );

        $tools->register(
            new ExecuteCommandTool($commandManager),
            new GetCommandDetailsTool($commandManager),
            new GetCommandsListTool($commandManager),
            new ReadFileTool($dirs->get('app')),
            new WriteFileTool($dirs->get('app')),
        );
    }
}