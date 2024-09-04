<?php

declare(strict_types=1);

namespace LLM\Agents\Agent\SymfonyConsole;

use LLM\Agents\Agent\AgentFactoryInterface;
use LLM\Agents\Agent\AgentInterface;

final readonly class SymfonyConsoleAgentFactory implements AgentFactoryInterface
{
    public function __construct(
        private string $frameworkName,
    ) {}

    public function create(): AgentInterface
    {
        return SymfonyConsoleAgent::create(frameworkName: $this->frameworkName);
    }
}
