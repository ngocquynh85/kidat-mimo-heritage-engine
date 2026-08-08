<?php

declare(strict_types=1);

namespace KidatTest\Pipeline;

use Kidat\AI\MiMoClient;
use Kidat\Domain\SlabJob;
use Kidat\Pipeline\HeritagePipeline;
use PHPUnit\Framework\TestCase;

final class HeritagePipelineTest extends TestCase
{
    public function testMockPipelinePreservesAuditableStages(): void
    {
        $pipeline = new HeritagePipeline(
            new MiMoClient('https://example.invalid/v1', null, true),
            [
                'ocr' => 'mimo-v2.5-omni',
                'reconstruction' => 'mimo-v2.5-pro',
                'translation' => 'mimo-v2.5-pro',
                'review' => 'mimo-v2.5-pro',
            ],
        );

        $result = $pipeline->run(new SlabJob(
            slabNumber: 1,
            title: 'Synthetic fixture',
            imagePath: 'fixtures/images/slab-001-placeholder.jpg',
            referenceContext: 'Synthetic reference context',
        ));

        self::assertTrue($result['mocked']);
        self::assertSame(1, $result['slab_number']);
        self::assertSame(
            ['ocr', 'reconstruction', 'translation', 'review'],
            array_keys($result['stages']),
        );
        self::assertSame(4800, $result['usage']['input_tokens']);
        self::assertSame(2000, $result['usage']['output_tokens']);
        self::assertFalse($result['stages']['review']['approved']);
    }
}
