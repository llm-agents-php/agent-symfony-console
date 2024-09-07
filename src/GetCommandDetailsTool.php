<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use LLM\Agents\Tool\PhpTool;
use LLM\Agents\Tool\ToolLanguage;

/**
 * @extends PhpTool<GetCommandDetailsInput>
 */
final class GetCommandDetailsTool extends PhpTool
{
    public const NAME = 'get_command_details';

    public function __construct(
        private readonly CommandManagerInterface $application,
    ) {
        parent::__construct(
            name: self::NAME,
            inputSchema: GetCommandDetailsInput::class,
            description: 'Retrieves detailed information about a specific console command, including usage, arguments, and options.',
        );
    }

    public function getLanguage(): ToolLanguage
    {
        return ToolLanguage::PHP;
    }

    public function execute(object $input): string
    {
        $details = $this->application->getCommandHelp($input->command);

        return \json_encode([
            'help' => $details,
        ]);
    }
}
