<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use LLM\Agents\Tool\Tool;
use LLM\Agents\Tool\ToolLanguage;

final class GetCommandsListTool extends Tool
{
    public const NAME = 'get_commands_list';

    public function __construct(
        private readonly CommandManagerInterface $application,
    ) {
        parent::__construct(
            name: self::NAME,
            inputSchema: GetCommandsListInput::class,
            description: 'Retrieves a list of available console commands with their descriptions.',
        );
    }

    public function getLanguage(): ToolLanguage
    {
        return ToolLanguage::PHP;
    }

    public function execute(object $input): string
    {
        $commands = $this->application->getCommands();
        $result = [];

        foreach ($commands as $name => $command) {
            $result[] = [
                'name' => $name,
                'description' => $command->getDescription(),
            ];
        }

        return \json_encode($result);
    }
}
