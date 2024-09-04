<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use LLM\Agents\Tool\Tool;
use LLM\Agents\Tool\ToolLanguage;

final class ReadFileTool extends Tool
{
    public const NAME = 'read_file';

    public function __construct(
        private readonly string $basePath,
    ) {
        parent::__construct(
            name: self::NAME,
            inputSchema: ReadFileInput::class,
            description: 'This tool reads the content of a file at the given path.',
        );
    }

    public function getLanguage(): ToolLanguage
    {
        return ToolLanguage::PHP;
    }

    public function execute(object $input): string
    {
        $path = \rtrim($this->basePath, '/') . '/' . \ltrim($input->path, '/');

        if (!file_exists($path)) {
            return \json_encode(['error' => 'File not found']);
        }

        $content = \file_get_contents($path);

        if ($content === false) {
            return \json_encode(['error' => 'Unable to read file']);
        }

        return \json_encode([
            'content' => $content,
            'path' => $input->path,
        ]);
    }
}
