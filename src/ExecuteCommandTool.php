<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use LLM\Agents\Tool\PhpTool;
use LLM\Agents\Tool\ToolLanguage;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @extends PhpTool<ExecuteCommandInput>
 */
final class ExecuteCommandTool extends PhpTool
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
            $value = $option->value;

            if ($option->value === '') {
                $value = true;
            }
            $arguments[$option->key][] = $value;
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
