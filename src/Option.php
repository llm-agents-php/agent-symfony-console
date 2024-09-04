<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use Spiral\JsonSchemaGenerator\Attribute\Field;

final readonly class Option
{
    public function __construct(
        #[Field(title: 'Key', description: 'The key of the option')]
        public string $key,

        #[Field(title: 'Value', description: 'The value of the option')]
        public string $value,
    ) {}
}
