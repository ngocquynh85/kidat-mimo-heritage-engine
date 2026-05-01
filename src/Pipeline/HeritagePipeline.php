<?php

declare(strict_types=1);

namespace Kidat\Pipeline;

use Kidat\AI\MiMoClient;
use Kidat\Domain\SlabJob;

final class HeritagePipeline
{
    public function __construct(
        private readonly MiMoClient $mimo,
        private readonly array $models,
    ) {}

    public function run(SlabJob $job): array
    {
        $ocr = $this->mimo->generateJson($this->models['ocr'], [
            ['role' => 'system', 'content' => 'Extract visible inscription text. Preserve uncertainty. Return JSON only.'],
            ['role' => 'user', 'content' => "Slab {$job->slabNumber}: {$job->title}. Image: {$job->imagePath}"],
        ], ['stage' => 'ocr']);

        $reconstruction = $this->mimo->generateJson($this->models['reconstruction'], [
            ['role' => 'system', 'content' => 'Suggest reconstruction candidates with evidence and confidence. Return JSON only.'],
            ['role' => 'user', 'content' => json_encode(['ocr' => $ocr->payload, 'reference_context' => $job->referenceContext], JSON_UNESCAPED_UNICODE)],
        ], ['stage' => 'reconstruction']);

        $translation = $this->mimo->generateJson($this->models['translation'], [
            ['role' => 'system', 'content' => 'Translate restored inscription text into English and Vietnamese. Return JSON only.'],
            ['role' => 'user', 'content' => json_encode($reconstruction->payload, JSON_UNESCAPED_UNICODE)],
        ], ['stage' => 'translation']);

        $review = $this->mimo->generateJson($this->models['review'], [
            ['role' => 'system', 'content' => 'Review hallucination risk, unsupported reconstruction, and terminology consistency. Return JSON only.'],
            ['role' => 'user', 'content' => json_encode([
                'ocr' => $ocr->payload,
                'reconstruction' => $reconstruction->payload,
                'translation' => $translation->payload,
            ], JSON_UNESCAPED_UNICODE)],
        ], ['stage' => 'review']);

        return [
            'slab_number' => $job->slabNumber,
            'mocked' => $ocr->mocked || $reconstruction->mocked || $translation->mocked || $review->mocked,
            'stages' => [
                'ocr' => $ocr->payload,
                'reconstruction' => $reconstruction->payload,
                'translation' => $translation->payload,
                'review' => $review->payload,
            ],
            'usage' => [
                'input_tokens' => $ocr->inputTokens + $reconstruction->inputTokens + $translation->inputTokens + $review->inputTokens,
                'output_tokens' => $ocr->outputTokens + $reconstruction->outputTokens + $translation->outputTokens + $review->outputTokens,
            ],
        ];
    }
}
