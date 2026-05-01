<?php

declare(strict_types=1);

namespace Kidat\AI;

use Kidat\Support\Env;
use RuntimeException;

final class MiMoClient
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly ?string $apiKey,
        private readonly bool $mock = true,
    ) {
    }

    public static function fromEnv(): self
    {
        return new self(
            Env::get('KIDAT_MIMO_BASE_URL', 'https://platform.xiaomimimo.com/v1'),
            Env::get('KIDAT_MIMO_API_KEY'),
            Env::bool('KIDAT_MIMO_MOCK', true),
        );
    }

    public function generateJson(string $model, array $messages, array $schemaHint = []): MiMoResponse
    {
        if ($this->mock || !$this->apiKey) {
            return $this->mockResponse($model, $messages, $schemaHint);
        }

        // OpenAI-compatible placeholder. Adjust endpoint/body when MiMo public API docs are finalized.
        $body = $this->postJson('chat/completions', [
            'model' => $model,
            'messages' => $messages,
            'response_format' => ['type' => 'json_object'],
        ]);
        if (!is_array($body)) {
            throw new RuntimeException('MiMo API returned invalid JSON.');
        }

        $content = $body['choices'][0]['message']['content'] ?? '{}';
        $payload = json_decode($content, true);
        if (!is_array($payload)) {
            throw new RuntimeException('MiMo model response was not a JSON object.');
        }

        return new MiMoResponse(
            model: $model,
            payload: $payload,
            inputTokens: (int) ($body['usage']['prompt_tokens'] ?? 0),
            outputTokens: (int) ($body['usage']['completion_tokens'] ?? 0),
            mocked: false,
        );
    }

    private function postJson(string $path, array $body): array
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');
        $payload = json_encode($body, JSON_UNESCAPED_UNICODE);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", [
                    'Authorization: Bearer ' . $this->apiKey,
                    'Content-Type: application/json',
                    'Accept: application/json',
                ]),
                'content' => $payload,
                'timeout' => 60,
                'ignore_errors' => true,
            ],
        ]);

        $raw = file_get_contents($url, false, $context);
        if ($raw === false) {
            throw new RuntimeException('MiMo API request failed.');
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('MiMo API returned invalid JSON body.');
        }

        return $decoded;
    }

    private function mockResponse(string $model, array $messages, array $schemaHint): MiMoResponse
    {
        $stage = $schemaHint['stage'] ?? 'unknown';

        $payload = match ($stage) {
            'ocr' => [
                'visible_text' => '[demo placeholder: visible inscription text would appear here]',
                'uncertain_spans' => [['span' => '[unclear]', 'reason' => 'synthetic demo fixture']],
                'damaged_regions' => [],
                'script_notes' => 'Mock OCR output for pipeline validation only.',
                'confidence' => 0.42,
            ],
            'reconstruction' => [
                'restored_text_candidate' => '[demo placeholder: restored text candidate]',
                'changed_spans' => [],
                'evidence' => ['No real evidence attached in mock mode.'],
                'alternative_candidates' => [],
                'confidence' => 0.31,
                'needs_human_review' => true,
            ],
            'translation' => [
                'english_translation' => '[demo placeholder translation]',
                'vietnamese_translation' => '[bản dịch demo placeholder]',
                'key_terms' => [],
                'translation_notes' => 'Mock translation; not scholarly output.',
                'confidence' => 0.30,
            ],
            'review' => [
                'approved' => false,
                'issues' => ['Mock fixture is not a verified source.'],
                'recommended_revisions' => ['Attach real slab image and canonical references.'],
                'confidence' => 0.80,
            ],
            default => ['message' => 'Mock MiMo response', 'stage' => $stage],
        };

        return new MiMoResponse(
            model: $model,
            payload: $payload,
            inputTokens: 1200,
            outputTokens: 500,
            mocked: true,
        );
    }
}
