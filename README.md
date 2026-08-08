# KIDAT — Kuthodaw AI Heritage Engine

KIDAT is a provider-neutral, AI-assisted cultural heritage pipeline for digitizing, restoring, cross-referencing, and translating the 729 marble inscription slabs of the Kuthodaw Pagoda in Myanmar. MiMo is the first model adapter, not a dependency of the core pipeline contract.

[![CI](https://github.com/ngocquynh85/kidat-mimo-heritage-engine/actions/workflows/ci.yml/badge.svg)](https://github.com/ngocquynh85/kidat-mimo-heritage-engine/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

<p align="center">
  <a href="https://demo.tutu.mobi/kidat/">
    <img src="docs/assets/kidat-demo-hero.png" alt="KIDAT interactive demo hero screen" width="100%">
  </a>
</p>

- **Public interactive demo:** <https://demo.tutu.mobi/kidat/>
- **GitHub repository:** <https://github.com/ngocquynh85/kidat-mimo-heritage-engine>

> **Status:** early-stage public OSS prototype. The repository currently validates the data model, MiMo-oriented workflow, prompt templates, token accounting, provenance policy, and worker architecture before real full-corpus processing.

### What is working now

- A reproducible PHP CLI pipeline with a deterministic mock mode.
- A provider-neutral `ModelClient` contract with MiMo as the first adapter.
- Separate OCR, uncertainty, reconstruction, translation, and review stages.
- A 734-record source manifest with provenance and licensing status fields.
- A public interactive prototype showing the intended reviewer workflow.
- PHPUnit coverage and GitHub Actions checks for the core scaffold.

### What is not claimed

The repository does not claim completed scholarly transcription, verified
translations, or full-corpus AI processing. Demo outputs are synthetic and
clearly labeled. Real source processing requires licensed data access and
human review.

## Interactive prototype

The public prototype is a visual proof-of-work for the MiMo application. It demonstrates the intended corpus scale, model-routing workflow, slab-level OCR/restoration/review interface, and token-demand rationale.

<p align="center">
  <a href="https://demo.tutu.mobi/kidat/">
    <img src="docs/assets/kidat-demo-slab-lab.png" alt="KIDAT slab OCR restoration review simulation" width="100%">
  </a>
</p>

> The demo is illustrative. Full MiMo-powered corpus processing will run after API quota is granted. Demo inscription text and slab visualization are placeholders, not verified Kuthodaw transcriptions.

## Why this project exists

The Kuthodaw inscriptions are a historically significant Buddhist textual corpus, but they are difficult to process with a conventional OCR workflow. The source material is visual, historical, multilingual, and may contain degraded or uncertain characters.

KIDAT explores a careful AI workflow that keeps every intermediate step auditable: raw OCR, uncertain spans, restoration candidates, translations, confidence scores, references, and review notes are stored separately.

## Model strategy

The core pipeline depends on a small provider-neutral `ModelClient` contract.
The first adapter uses Xiaomi MiMo's multimodal and reasoning capabilities:

- **MiMo-V2.5-Omni** — visual OCR, inscription layout analysis, and degraded-character inspection.
- **MiMo-V2.5-Pro** — restoration reasoning, canonical cross-reference, terminology consistency, and multilingual translation.
- **MiMo-V2.5-Flash** — lower-cost metadata extraction, segmentation, classification, and batch quality checks.

The goal is not to produce a single polished answer. The goal is to produce structured, reviewable evidence at each stage.

## Pipeline

```mermaid
flowchart TD
  A[Slab image / crop] --> B[Metadata registration]
  B --> C[Visual OCR]
  C --> D[Uncertainty detection]
  D --> E[Restoration candidates]
  E --> F[Canonical cross-reference]
  F --> G[Multilingual translation]
  G --> H[Glossary and hallucination review]
  H --> I[Versioned storage]
```

## Quick start

```bash
composer install
cp .env.example .env
php bin/kidat demo
php bin/kidat estimate
vendor/bin/phpunit --configuration phpunit.xml.dist
```

The default mode is mock mode, so the demo pipeline can run without API credentials:

```env
KIDAT_MIMO_MOCK=true
```

Real MiMo API mode can be enabled later through `.env` after credentials and endpoint details are available.

Additional provider adapters can implement `ModelClient` without changing the
pipeline's evidence model, stage boundaries, or human-review policy.


## Data source strategy

KIDAT references an external public Kuthodaw image viewer repository as a candidate image source:

- Upstream: <https://github.com/kit119/KIT-729>
- Manifest: `data/upstream_kit729_manifest.jsonl`
- Inventory: 734 `.webp` image files, about 804 MB upstream

The full image corpus is not copied into this repository because the upstream repository does not declare a clear redistribution license. KIDAT stores source metadata and URLs only; real processing should use approved local copies or licensed source access.

## Repository contents

- `docs/application_english.md` — application draft for Xiaomi MiMo Orbit.
- `docs/token_justification.md` — token-demand rationale and scaling plan.
- `docs/assets/` — screenshots from the public interactive prototype.
- `docs/architecture.md` — technical architecture and data policy.
- `docs/development.md` — local development notes.
- `prompts/` — prompt templates for OCR, restoration, translation, and review.
- `sql/schema.sql` — initial MySQL schema for versioned records.
- `src/` — PHP scaffold: MiMo client, pipeline, domain model, and token estimator.
- `src/AI/ModelClient.php` — provider-neutral structured-generation contract.
- `fixtures/` — synthetic demo fixture for validating the pipeline shape.
- `data/` — upstream image-source manifest and licensing notes.
- `scripts/inspect_manifest.php` — quick manifest inventory check.
- `tests/` — deterministic tests for token planning and the mock pipeline.
- `.github/workflows/ci.yml` — PHP compatibility, lint, test, and smoke checks.
- `CONTRIBUTING.md` / `SECURITY.md` — public maintainer and responsible-data guidelines.

## Design principles

- Preserve raw OCR, restored text, translations, confidence, and review notes separately.
- Mark uncertainty explicitly; do not silently fill missing text.
- Require evidence and confidence for every restoration candidate.
- Use cheaper models for routine batch work and stronger models for difficult reasoning.
- Start with a small pilot, then scale through worker queues.

## Important note

This repository is a prototype scaffold. Demo outputs are placeholders for pipeline validation and are not verified scholarly transcriptions or translations.
