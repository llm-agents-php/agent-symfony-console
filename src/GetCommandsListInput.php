<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use Spiral\JsonSchemaGenerator\Attribute\Field;

final readonly class GetCommandsListInput
{
    public function __construct(
        #[Field(title: 'Namespace', description: 'Optional namespace to filter commands. Empty string to retrieve all commands')]
        public string $namespace,
    ) {}
}
