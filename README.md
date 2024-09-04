# Symfony Console Agent 🎭

[![PHP](https://img.shields.io/packagist/php-v/llm-agents/agent-symfony-console.svg?style=flat-square)](https://packagist.org/packages/llm-agents/agent-symfony-console)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/llm-agents/agent-symfony-console.svg?style=flat-square)](https://packagist.org/packages/llm-agents/agent-symfony-console)
[![Total Downloads](https://img.shields.io/packagist/dt/llm-agents/agent-symfony-console.svg?style=flat-square)](https://packagist.org/packages/llm-agents/agent-symfony-console)

Welcome to the Symfony Console Agent, your new bestie for turning natural language into command-line magic. This nifty
agent understands your human babble and translates it into console commands. It's like having a super-smart CLI
assistant right at your fingertips!

### What's the deal? 🤔

Ever wished you could just tell your console what to do in plain English? Well, now you can! This agent takes your
casual requests and figures out which Symfony command to run. It's perfect for devs who are new to a project, can't
remember exact command syntax, or just feeling a bit lazy (we've all been there).

**Here's a short video to show you how it works:**

[![Symfony Console Agent Demo](https://github.com/user-attachments/assets/e3a0efd6-486f-48d6-a90a-5595efa9fc45)](https://youtu.be/Tlu6PyL3Ur4)

### Requirements 📋

- PHP 8.3 or higher (we're living in the future, baby!)
- Symfony Console component
- A sense of humor (optional, but highly recommended)

### Installation

First, make sure you've got Composer installed. Then, run this command to add the Symfony Console Agent to your project:

```bash
composer require llm-agents/agent-symfony-console
```

### Setup in Spiral Framework

1. Open up your `app/src/Application/Kernel.php` file.
2. Add the bootloader like this:
   ```php
   public function defineBootloaders(): array
   {
       return [
           // ... other bootloaders ...
           \LLM\Agents\Agent\SymfonyConsole\Integrations\Spiral\SymfonyConsoleBootloader::class,
       ];
   }
   ```

And that's it! Your Spiral app is now ready to use the agent.

### Laravel

For Laravel, there's a service provider you can use:

```php
// bootstrap/providers.php
return [
    // ... other providers ...
    LLM\Agents\Agent\SymfonyConsole\Integrations\Laravel\SymfonyConsoleServiceProvider::class,
];
```

## Usage

To start using the Symfony Console Agent, you'll need to implement the
`LLM\Agents\Agent\SymfonyConsole\CommandManagerInterface`. interface. This interface is responsible for fetching command
help, listing available commands, and executing commands.

**Here's an example of how you might use it in a Laravel application:**

```php
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
        return $this->application->call($command, $parameters, $output);
    }
}
```

## Class diagram

```mermaid
classDiagram
    class SymfonyConsoleAgent {
        +NAME: string
        +create(frameworkName: string): self
        -addMetadata(SolutionMetadata): void
        -addAssociation(Solution): void
    }
    class AgentAggregate {
        <<interface>>
        +getKey(): string
        +getName(): string
        +getDescription(): string
        +getInstruction(): string
        +getTools(): array
        +getAgents(): array
        +getModel(): Model
        +getMemory(): array
        +getPrompts(): array
        +getConfiguration(): array
    }
    class GetCommandsListTool {
        +NAME: string
        -application: CommandManagerInterface
        +__construct(CommandManagerInterface)
        +execute(input: object): string
    }
    class GetCommandDetailsTool {
        +NAME: string
        -application: CommandManagerInterface
        +__construct(CommandManagerInterface)
        +execute(input: object): string
    }
    class ExecuteCommandTool {
        +NAME: string
        -application: CommandManagerInterface
        +__construct(CommandManagerInterface)
        +execute(input: object): string
    }
    class ReadFileTool {
        +NAME: string
        -basePath: string
        +__construct(string)
        +execute(input: object): string
    }
    class WriteFileTool {
        +NAME: string
        -basePath: string
        +__construct(string)
        +execute(input: object): string
    }
    class CommandManagerInterface {
        <<interface>>
        +getCommandHelp(command: string): string
        +getCommands(): array
        +call(command: string, parameters: array, output: OutputInterface): int
    }
    class Tool {
        <<abstract>>
        +getName(): string
        +getDescription(): string
        +getInputSchema(): string
        +getLanguage(): ToolLanguage
        +execute(input: object): string|Stringable
    }
    class ToolInterface {
        <<interface>>
        +getName(): string
        +getDescription(): string
        +getInputSchema(): string
        +getLanguage(): ToolLanguage
        +execute(input: object): string|Stringable
    }
    class SymfonyConsoleAgentFactory {
        -frameworkName: string
        +__construct(frameworkName: string)
        +create(): AgentInterface
    }
    class AgentFactoryInterface {
        <<interface>>
        +create(): AgentInterface
    }
    class GetCommandsListInput {
        +namespace: string
    }
    class GetCommandDetailsInput {
        +command: string
    }
    class ExecuteCommandInput {
        +command: string
        +arguments: array
        +options: array
    }
    class ReadFileInput {
        +path: string
    }
    class WriteFileInput {
        +path: string
        +content: string
    }

    SymfonyConsoleAgent --|> AgentAggregate
    SymfonyConsoleAgent ..> GetCommandsListTool
    SymfonyConsoleAgent ..> GetCommandDetailsTool
    SymfonyConsoleAgent ..> ExecuteCommandTool
    SymfonyConsoleAgent ..> ReadFileTool
    SymfonyConsoleAgent ..> WriteFileTool
    GetCommandsListTool --|> Tool
    GetCommandDetailsTool --|> Tool
    ExecuteCommandTool --|> Tool
    ReadFileTool --|> Tool
    WriteFileTool --|> Tool
    Tool ..|> ToolInterface
    GetCommandsListTool ..> CommandManagerInterface
    GetCommandDetailsTool ..> CommandManagerInterface
    ExecuteCommandTool ..> CommandManagerInterface
    SymfonyConsoleAgentFactory ..|> AgentFactoryInterface
    SymfonyConsoleAgentFactory ..> SymfonyConsoleAgent
    GetCommandsListTool ..> GetCommandsListInput
    GetCommandDetailsTool ..> GetCommandDetailsInput
    ExecuteCommandTool ..> ExecuteCommandInput
    ReadFileTool ..> ReadFileInput
    WriteFileTool ..> WriteFileInput
```

## Want to help out? 🤝

We love contributions! If you've got ideas to make this agent even cooler, here's how you can chip in:

1. Fork the repo
2. Make your changes
3. Create a new Pull Request

Just make sure your code is clean, well-commented, and follows PSR-12 coding standards.

## License 📄

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

That's all, folks! If you've got any questions or run into any trouble, don't hesitate to open an issue.