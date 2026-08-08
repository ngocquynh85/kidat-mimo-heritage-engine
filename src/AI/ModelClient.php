<?php

declare(strict_types=1);

namespace Kidat\AI;

interface ModelClient
{
    public function generateJson(
        string $model,
        array $messages,
        array $schemaHint = [],
    ): ModelResponse;
}
