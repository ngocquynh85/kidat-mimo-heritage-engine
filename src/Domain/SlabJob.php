<?php

declare(strict_types=1);

namespace Kidat\Domain;

final readonly class SlabJob
{
    public function __construct(
        public int $slabNumber,
        public string $title,
        public string $imagePath,
        public string $referenceContext = '',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            slabNumber: (int) $data['slab_number'],
            title: (string) ($data['title'] ?? 'Untitled slab'),
            imagePath: (string) ($data['image_path'] ?? ''),
            referenceContext: (string) ($data['reference_context'] ?? ''),
        );
    }
}
