<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use LLM\Agents\Tool\Tool;
use LLM\Agents\Tool\ToolLanguage;
use Symfony\Component\Console\Output\BufferedOutput;

final class ExecuteCommandTool extends Tool
{
    public const NAME = 'execute_command';

    public function __construct(
        private readonly CommandManagerInterface $application,
    ) {
        parent::__construct(
            name: self::NAME,
            inputSchema: ExecuteCommandInput::class,
            description: 'This tool executes a console command with the provided arguments and options.',
        );
    }

    public function getLanguage(): ToolLanguage
    {
        return ToolLanguage::PHP;
    }

    public function execute(object $input): string
    {
        $arguments = [];

        foreach ($input->arguments as $argument) {
            $arguments[$argument->key] = $argument->value;
        }

        foreach ($input->options as $option) {
            $arguments[$option->key] = $option->value;
        }

        $output = new BufferedOutput();

        try {
            $exitCode = $this->application->call($input->command, $arguments, $output);
            $result = [
                'success' => $exitCode === 0,
                'output' => $output->fetch(),
                'exit_code' => $exitCode,
            ];
        } catch (\Exception $e) {
            $result = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return \json_encode($result);
    }
}
