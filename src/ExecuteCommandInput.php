<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use Spiral\JsonSchemaGenerator\Attribute\Field;

final readonly class ExecuteCommandInput
{
    public function __construct(
        #[Field(title: 'Command', description: 'The name of the command to execute')]
        public string $command,

        /** @var array<Argument> */
        #[Field(title: 'Arguments', description: 'Command arguments')]
        public array $arguments,

        /** @var array<Option> */
        #[Field(title: 'Options', description: 'Command options')]
        public array $options,
    ) {}
}
