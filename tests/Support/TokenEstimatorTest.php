<?php

declare(strict_types=1);

namespace KidatTest\Support;

use Kidat\Support\TokenEstimator;
use PHPUnit\Framework\TestCase;

final class TokenEstimatorTest extends TestCase
{
    public function testDefaultEstimateIsDeterministic(): void
    {
        $estimate = (new TokenEstimator())->estimate();

        self::assertSame(729, $estimate['slabs']);
        self::assertSame(40824, $estimate['estimated_model_calls']);
        self::assertSame(1102248000, $estimate['estimated_tokens']);
    }

    public function testCustomEstimateUsesAllPlanningInputs(): void
    {
        $estimate = (new TokenEstimator())->estimate([
            'slabs' => 2,
            'regions_per_slab' => 3,
            'stages_per_region' => 4,
            'avg_input_tokens' => 100,
            'avg_output_tokens' => 50,
            'retry_factor' => 2.0,
        ]);

        self::assertSame(24, $estimate['estimated_model_calls']);
        self::assertSame(7200, $estimate['estimated_tokens']);
    }
}
