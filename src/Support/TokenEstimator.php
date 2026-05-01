<?php

declare(strict_types=1);

namespace Kidat\Support;

final class TokenEstimator
{
    public function estimate(array $options = []): array
    {
        $slabs = (int) ($options['slabs'] ?? 729);
        $regionsPerSlab = (int) ($options['regions_per_slab'] ?? 8);
        $stagesPerRegion = (int) ($options['stages_per_region'] ?? 7);
        $avgInput = (int) ($options['avg_input_tokens'] ?? 16000);
        $avgOutput = (int) ($options['avg_output_tokens'] ?? 4000);
        $retryFactor = (float) ($options['retry_factor'] ?? 1.35);

        $calls = $slabs * $regionsPerSlab * $stagesPerRegion;
        $tokens = (int) round($calls * ($avgInput + $avgOutput) * $retryFactor);

        return [
            'slabs' => $slabs,
            'regions_per_slab' => $regionsPerSlab,
            'stages_per_region' => $stagesPerRegion,
            'estimated_model_calls' => $calls,
            'retry_factor' => $retryFactor,
            'estimated_tokens' => $tokens,
            'note' => 'Planning estimate only; real usage depends on image segmentation, context size, retries, and review depth.',
        ];
    }
}
