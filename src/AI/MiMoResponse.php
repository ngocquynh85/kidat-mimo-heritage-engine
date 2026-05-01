<?php

declare(strict_types=1);

namespace Kidat\AI;

final readonly class MiMoResponse
{
    public function __construct(
        public string $model,
        public array $payload,
        public int $inputTokens = 0,
        public int $outputTokens = 0,
        public bool $mocked = false,
    ) {}
}
