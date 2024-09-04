<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use Spiral\JsonSchemaGenerator\Attribute\Field;

final readonly class WriteFileInput
{
    public function __construct(
        #[Field(title: 'Path', description: 'The path to the file to be written')]
        public string $path,

        #[Field(title: 'Content', description: 'The content to be written to the file')]
        public string $content,
    ) {}
}
