<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use Spiral\JsonSchemaGenerator\Attribute\Field;

final readonly class GetCommandDetailsInput
{
    public function __construct(
        #[Field(title: 'Command', description: 'The name of the command to get details for')]
        public string $command,
    ) {}
}
