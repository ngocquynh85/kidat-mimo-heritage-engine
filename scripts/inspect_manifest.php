#!/usr/bin/env php
<?php

declare(strict_types=1);

$manifest = $argv[1] ?? __DIR__ . '/../data/upstream_kit729_manifest.jsonl';
if (!is_file($manifest)) {
    fwrite(STDERR, "Manifest not found: {$manifest}\n");
    exit(1);
}

$count = 0;
$bytes = 0;
$first = null;
$last = null;

$fh = fopen($manifest, 'rb');
while (($line = fgets($fh)) !== false) {
    $row = json_decode($line, true);
    if (!is_array($row)) {
        continue;
    }
    $count++;
    $bytes += (int) ($row['byte_size'] ?? 0);
    $first ??= $row['file_name'] ?? null;
    $last = $row['file_name'] ?? $last;
}
fclose($fh);

echo json_encode([
    'manifest' => $manifest,
    'files' => $count,
    'total_bytes' => $bytes,
    'total_mb' => round($bytes / 1024 / 1024, 2),
    'first' => $first,
    'last' => $last,
    'note' => 'Manifest references upstream images; this repository does not redistribute the corpus.',
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
