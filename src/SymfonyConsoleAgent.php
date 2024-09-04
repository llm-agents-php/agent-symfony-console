<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use LLM\Agents\Agent\AgentAggregate;
use LLM\Agents\Agent\Agent;
use LLM\Agents\Solution\Model;
use LLM\Agents\Solution\ToolLink;
use LLM\Agents\Solution\MetadataType;
use LLM\Agents\Solution\SolutionMetadata;

final class SymfonyConsoleAgent extends AgentAggregate
{
    public const NAME = 'symfony_console_agent';

    public static function create(
        string $frameworkName = 'Laravel',
    ): self {
        $agent = new Agent(
            key: self::NAME,
            name: 'Symfony Console Agent',
            description: 'This agent is designed to interact with console commands.',
            instruction: \sprintf(
                <<<'PROMPT'
You are an expert in %s framework.
You are a console command executor.
Your primary goal is to help users interact with console commands.
PROMPT,
                $frameworkName,
            ),
        );

        $aggregate = new self($agent);

        $aggregate->addMetadata(
            new SolutionMetadata(
                type: MetadataType::Memory,
                key: 'console_agent',
                content: \sprintf(
                    <<<'PROMPT'
This agent is designed to interact with console commands for %s framework.',
PROMPT,
                    $frameworkName,
                ),
            ),

            new SolutionMetadata(
                type: MetadataType::Memory,
                key: 'available_tools',
                content: <<<'PROMPT'
The Agent provides tools:
- get_commands_list,
- get_command_details,
- execute_command,
- read_file,
- write_file.
There is no other way to interact with the console commands.
PROMPT,
            ),

            new SolutionMetadata(
                type: MetadataType::Memory,
                key: 'command_details',
                content: 'Always run `get_command_details` before the first command usage to understand what options and arguments are available.',
            ),

            new SolutionMetadata(
                type: MetadataType::Memory,
                key: 'tools_usage',
                content: 'Before start always run `get_commands_list` to understand commands purpose.',
            ),

            new SolutionMetadata(
                type: MetadataType::Memory,
                key: 'your_goal',
                content: 'A user provides a desired command to execute, and you should run it with the provided arguments and options.',
            ),

            new SolutionMetadata(
                type: MetadataType::Memory,
                key: 'update_code',
                content: 'Some commands may require additional code to be added to the project.',
            ),

            new SolutionMetadata(
                type: MetadataType::Memory,
                key: 'steps',
                content: <<<'PROMPT'
Think step by step. Run commands one by one without additional approves.
If you need to run multiple commands or tools, do it one by one.
PROMPT,
            ),

            new SolutionMetadata(
                type: MetadataType::Memory,
                key: 'only_available_tools',
                content: 'Do not make up tools that are not available in the Agent. Use tool `execute_command` to run console commands.',
            ),

            new SolutionMetadata(
                type: MetadataType::Memory,
                key: 'only_available_tools',
                content: <<<'PROMPT'
To create files always use console commands.
Do not use `write_file` tool to create files, only for writing content to existing files.
PROMPT,
            ),

            new SolutionMetadata(
                type: MetadataType::Memory,
                key: 'answer',
                content: 'Write only short summary answers. Do not provide long explanations and code snippets.',
            ),

            new SolutionMetadata(
                type: MetadataType::Configuration,
                key: 'max_tokens',
                content: 3_000,
            ),

            new SolutionMetadata(
                type: MetadataType::Prompt,
                key: 'create_user_model',
                content: 'Generate a Blog model with the following fields: title, content, and published_at.',
            ),

            new SolutionMetadata(
                type: MetadataType::Prompt,
                key: 'create_auth_middleware',
                content: 'Create a new middleware called AuthenticateAdmin to check if a user is an admin.',
            ),

            new SolutionMetadata(
                type: MetadataType::Prompt,
                key: 'create_order_event',
                content: 'Make an event class named OrderPlaced for when a new order is created.',
            ),

            new SolutionMetadata(
                type: MetadataType::Prompt,
                key: 'create_api_resource',
                content: 'Generate a new API resource for the Product model.',
            ),

            new SolutionMetadata(
                type: MetadataType::Prompt,
                key: 'create_custom_validation',
                content: 'Create a new validation rule called StrongPassword to ensure passwords meet specific criteria.',
            ),

            new SolutionMetadata(
                type: MetadataType::Prompt,
                key: 'create_email_notification',
                content: 'Make a new notification class for sending welcome emails to new users.',
            ),

            new SolutionMetadata(
                type: MetadataType::Prompt,
                key: 'create_database_seeder',
                content: 'Generate a seeder class to populate the categories table with initial data.',
            ),

            new SolutionMetadata(
                type: MetadataType::Prompt,
                key: 'create_custom_artisan_command',
                content: 'Create a new Artisan command called SendWeeklyNewsletter to automate newsletter distribution.',
            ),

            new SolutionMetadata(
                type: MetadataType::Prompt,
                key: 'create_api_controller',
                content: 'Make a new API controller for handling CRUD operations on the Post model.',
            ),

            new SolutionMetadata(
                type: MetadataType::Prompt,
                key: 'create_database_migration',
                content: 'Generate a migration to add a `status` column to the orders table.',
            ),
        );

        $aggregate->addAssociation(new Model(model: 'gpt-4o-mini'));

        $aggregate->addAssociation(new ToolLink(name: GetCommandsListTool::NAME));
        $aggregate->addAssociation(new ToolLink(name: GetCommandDetailsTool::NAME));
        $aggregate->addAssociation(new ToolLink(name: ExecuteCommandTool::NAME));
        $aggregate->addAssociation(new ToolLink(name: ReadFileTool::NAME));
        $aggregate->addAssociation(new ToolLink(name: WriteFileTool::NAME));

        return $aggregate;
    }
}
