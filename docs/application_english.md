# Xiaomi MiMo Orbit Application Draft

## Project name

KIDAT — Kuthodaw AI Heritage Engine

## Short summary

KIDAT is an AI-assisted cultural heritage prototype for digitizing, restoring, cross-referencing, and translating the 729 marble inscription slabs of the Kuthodaw Pagoda in Myanmar, widely known as the “world’s largest book” and recognized by UNESCO Memory of the World.

The project uses Xiaomi MiMo models as the core AI engine for a staged, auditable workflow: visual OCR from high-resolution inscription images, uncertainty detection, restoration suggestions for degraded characters, cross-reference against canonical Pali/Burmese sources, and multilingual translation into English, Vietnamese, and modern Burmese.

## Why this project fits MiMo

KIDAT combines several capabilities that are central to MiMo:

1. **Multimodal understanding** — inscription images require visual reading, layout awareness, and damaged-character inspection.
2. **Deep reasoning** — restored readings must be justified against context and reference material, not guessed.
3. **Long-context comparison** — difficult passages may require chapter-level or corpus-level reference context.
4. **Multilingual translation** — the workflow targets Pali/Burmese source material and English/Vietnamese/modern Burmese outputs.
5. **Structured batch processing** — 729 slabs create a realistic long-running workload for API-based model orchestration.

Planned model routing:

- **MiMo-V2.5-Omni** for visual analysis, OCR, layout reading, and degraded inscription inspection.
- **MiMo-V2.5-Pro** for reconstruction reasoning, canonical comparison, terminology consistency, and high-quality translation.
- **MiMo-V2.5-Flash** for lower-cost metadata extraction, segmentation, classification, glossary checks, and preliminary quality review.

The project is not a generic chatbot. It is an auditable AI workflow where each model call produces structured intermediate outputs that can be reviewed, compared, versioned, and improved.

## Problem statement

The Kuthodaw inscriptions preserve an important Buddhist canonical corpus, but the material is difficult to digitize because the source is visual, historical, multilingual, and partially degraded. A simple OCR pipeline is not enough. The system needs to preserve uncertainty, compare readings against known references, enforce terminology consistency, and separate AI suggestions from reviewed outputs.

## AI workflow

For each slab or slab region, KIDAT runs a staged pipeline:

1. Image ingestion and metadata registration.
2. Visual OCR from high-resolution slab images.
3. Character-level uncertainty and damaged-region detection.
4. Restoration candidate generation for eroded or missing characters.
5. Cross-reference against canonical Pali/Burmese sources when available.
6. Translation into English, Vietnamese, and modern Burmese.
7. Terminology review using a controlled Buddhist/Pali glossary.
8. Hallucination and unsupported-restoration review.
9. Final consistency check and versioned storage.

The system separates raw OCR, restored text, translations, confidence scores, references, and human-review notes. This reduces hallucination risk and makes each output auditable.

## Token demand justification

KIDAT is token-intensive by design. The full target corpus contains 729 inscription slabs. Each slab may require multiple image regions, OCR passes, reconstruction passes, reference comparison, multilingual translation, terminology review, and consistency checks.

A realistic full-corpus workflow includes:

- 729 slabs × multiple image regions per slab
- multimodal OCR and visual reasoning per region
- 5–10 AI stages per region
- long-context canonical comparison for consistency
- multilingual translation and review
- iterative retries for low-confidence or damaged areas
- versioned reprocessing as prompts and glossary rules improve

The initial phase will start with a small pilot subset to validate quality and cost. After validation, the same worker-based architecture can scale to complete-corpus processing and repeated quality-improvement passes. The expected token path ranges from a billion-token pilot to tens or hundreds of billions for a first complete pass, and potentially much higher for long-context research review, low-confidence reprocessing, and multilingual versioning. This makes KIDAT a suitable high-value workload for a large MiMo token allocation.

## Technical implementation

The prototype repository includes a worker-centric architecture designed for batch processing:

- PHP 8.2+ worker runtime
- Ubuntu deployment target
- MySQL 8 for slab metadata, OCR records, restoration records, translation versions, confidence scores, and processing status
- Supervisor-compatible background workers
- MiMo API adapter with retry, rate-limit handling, token accounting, and structured JSON output validation
- Optional future Qdrant/Neo4j layer for semantic search and knowledge graph exploration
- Dashboard roadmap for progress tracking, confidence review, and side-by-side visual/text comparison

## Expected outputs

The project aims to produce:

- a structured digital archive for 729 Kuthodaw slabs
- searchable inscription text records
- restored-text candidates with confidence and evidence
- English, Vietnamese, and modern Burmese translations
- a Buddhist/Pali terminology glossary
- a public demo and research-ready dataset structure
- an auditable AI pipeline for cultural heritage preservation

## Current stage

KIDAT is currently a prototype/pilot repository. The first milestone is to validate the data schema, MiMo API integration layer, prompt templates, token accounting, and a small demonstration workflow for one or several sample slab images. The architecture is designed so the pilot can expand into full-corpus processing once sufficient MiMo API resources are available.
