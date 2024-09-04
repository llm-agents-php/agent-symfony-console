<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use Spiral\JsonSchemaGenerator\Attribute\Field;

final readonly class Argument
{
    public function __construct(
        #[Field(title: 'Key', description: 'The key of the argument')]
        public string $key,

        #[Field(title: 'Value', description: 'The value of the argument')]
        public string $value,
    ) {}
}
