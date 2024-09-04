<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use Spiral\JsonSchemaGenerator\Attribute\Field;

final readonly class ReadFileInput
{
    public function __construct(
        #[Field(title: 'Path', description: 'The path to the file to be read')]
        public string $path,
    ) {}
}
