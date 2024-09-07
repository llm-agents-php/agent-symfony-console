<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use LLM\Agents\Tool\PhpTool;
use LLM\Agents\Tool\Tool;
use LLM\Agents\Tool\ToolLanguage;

/**
 * @extends PhpTool<WriteFileInput>
 */
final class WriteFileTool extends Tool
{
    public const NAME = 'sc_write_file';

    public function __construct(
        private readonly string $basePath,
    ) {
        parent::__construct(
            name: self::NAME,
            inputSchema: WriteFileInput::class,
            description: 'This tool writes content to a file at the given path.',
        );
    }

    public function getLanguage(): ToolLanguage
    {
        return ToolLanguage::PHP;
    }

    public function execute(object $input): string
    {
        $path = \rtrim($this->basePath, '/') . '/' . \ltrim($input->path, '/');

        // Strip doubled slashes in code namespaces
        $content = \preg_replace('/\\\\{2,}/', '\\', $input->content);
        $result = \file_put_contents($path, $content);

        if ($result === false) {
            return \json_encode(['error' => 'Unable to write to file']);
        }

        return \json_encode([
            'success' => true,
            'bytes_written' => $result,
            'path' => $input->path,
        ]);
    }
}
