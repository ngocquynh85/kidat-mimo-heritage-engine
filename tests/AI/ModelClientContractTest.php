<?php

declare(strict_types=1);

namespace KidatTest\AI;

use Kidat\AI\ModelClient;
use Kidat\AI\ModelResponse;
use Kidat\Domain\SlabJob;
use Kidat\Pipeline\HeritagePipeline;
use PHPUnit\Framework\TestCase;

final class ModelClientContractTest extends TestCase
{
    public function testPipelineAcceptsAProviderNeutralClient(): void
    {
        $client = new class implements ModelClient {
            public array $stages = [];

            public function generateJson(
                string $model,
                array $messages,
                array $schemaHint = [],
            ): ModelResponse {
                $stage = (string) ($schemaHint['stage'] ?? 'unknown');
                $this->stages[] = $stage;

                return new ModelResponse(
                    model: $model,
                    payload: ['stage' => $stage],
                    inputTokens: 10,
                    outputTokens: 5,
                );
            }
        };

        $pipeline = new HeritagePipeline($client, [
            'ocr' => 'vision-model',
            'reconstruction' => 'reasoning-model',
            'translation' => 'translation-model',
            'review' => 'review-model',
        ]);

        $result = $pipeline->run(new SlabJob(1, 'Fixture', 'fixture.jpg'));

        self::assertSame(
            ['ocr', 'reconstruction', 'translation', 'review'],
            $client->stages,
        );
        self::assertFalse($result['mocked']);
        self::assertSame(40, $result['usage']['input_tokens']);
        self::assertSame(20, $result['usage']['output_tokens']);
    }
}
