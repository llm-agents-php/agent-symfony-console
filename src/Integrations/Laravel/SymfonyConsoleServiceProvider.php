<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole\Integrations\Laravel;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LLM\Agents\Agent\AgentRegistryInterface;
use LLM\Agents\Agent\SymfonyConsole\CommandManagerInterface;
use LLM\Agents\Agent\SymfonyConsole\ExecuteCommandTool;
use LLM\Agents\Agent\SymfonyConsole\GetCommandDetailsTool;
use LLM\Agents\Agent\SymfonyConsole\GetCommandsListTool;
use LLM\Agents\Agent\SymfonyConsole\ReadFileTool;
use LLM\Agents\Agent\SymfonyConsole\SymfonyConsoleAgentFactory;
use LLM\Agents\Agent\SymfonyConsole\WriteFileTool;
use LLM\Agents\Tool\ToolRegistryInterface;

final class SymfonyConsoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            SymfonyConsoleAgentFactory::class,
            static fn() => new SymfonyConsoleAgentFactory('Laravel'),
        );

        $this->app->singleton(
            CommandManagerInterface::class,
            static fn(Application $app) => new ArtisanCommandManager($app->get(Kernel::class)),
        );

        $this->app->singleton(
            ExecuteCommandTool::class,
            static fn(Application $app) => new ExecuteCommandTool($app->get(CommandManagerInterface::class)),
        );

        $this->app->singleton(
            GetCommandDetailsTool::class,
            static fn(Application $app) => new GetCommandDetailsTool($app->get(CommandManagerInterface::class)),
        );

        $this->app->singleton(
            GetCommandsListTool::class,
            static fn(Application $app) => new GetCommandsListTool($app->get(CommandManagerInterface::class)),
        );

        $this->app->singleton(
            ReadFileTool::class,
            static fn(Application $app) => new ReadFileTool($app->basePath()),
        );

        $this->app->singleton(
            WriteFileTool::class,
            static fn(Application $app) => new WriteFileTool($app->basePath()),
        );
    }

    public function boot(
        AgentRegistryInterface $agents,
        ToolRegistryInterface $tools,
        SymfonyConsoleAgentFactory $agentFactory,
        CommandManagerInterface $commandManager,
        Application $app,
    ): void {
        $agents->register(
            $agentFactory->create(),
        );

        $tools->register(
            new ExecuteCommandTool($commandManager),
            new GetCommandDetailsTool($commandManager),
            new GetCommandsListTool($commandManager),
            new ReadFileTool($app->basePath()),
            new WriteFileTool($app->basePath()),
        );
    }
}