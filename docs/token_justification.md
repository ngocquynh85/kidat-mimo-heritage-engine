# Token Justification

## Summary

KIDAT is a high-token, high-value workload because it is not a single-pass chatbot, summarizer, or translation script. It is a multimodal, multi-stage, versioned cultural heritage processing pipeline for 729 historical inscription slabs.

The project should be evaluated as a long-running AI production workload with several phases: pilot validation, full-corpus processing, low-confidence reprocessing, multilingual translation, terminology review, and versioned quality improvement.

## Corpus scale

- Target corpus: 729 Kuthodaw inscription slabs
- Each slab may require multiple photos, faces, or crop regions
- Each region may need multimodal OCR, reconstruction, translation, and review
- Outputs are versioned, so improved prompts, references, or glossary rules can trigger reprocessing

## Planned AI stages per region

1. Image quality assessment
2. Visual OCR using MiMo-V2.5-Omni
3. Uncertain character and damaged-region detection
4. Reconstruction candidate generation using MiMo-V2.5-Pro
5. Cross-reference against canonical Pali/Burmese sources when available
6. English translation
7. Vietnamese translation
8. Modern Burmese translation
9. Buddhist/Pali glossary consistency check
10. Hallucination and unsupported-reconstruction review
11. Final structured JSON validation and normalization

## Why repeated passes are necessary

Historical inscription processing has high uncertainty. A single model call is not reliable enough for degraded stone text. Low-confidence regions require retries, alternative reconstruction candidates, comparison with reference contexts, terminology checks, and final review.

The project intentionally separates OCR, reconstruction, translation, and review to make outputs auditable instead of silently generating a polished but unverifiable answer.

## Token usage scenarios

These are planning ranges, not claims of already consumed usage.

| Phase | Purpose | Estimated range |
|---|---|---:|
| Pilot | Validate schema, prompts, model selection, and review workflow on a small subset | 1B–5B tokens |
| First corpus pass | Process all 729 slabs with OCR, reconstruction, translation, and review | 50B–200B tokens |
| Quality improvement | Reprocess low-confidence regions, compare alternate prompts, improve glossary consistency | 200B–800B tokens |
| Long-context research mode | Chapter-level/corpus-level comparison and multi-agent review for difficult passages | 500B–2T+ tokens |

A large allocation is useful because the same repository can start with a small pilot and then scale into full-corpus batch processing once the workflow is validated.

## Why MiMo tokens are especially useful

- **MiMo-V2.5-Omni** is needed for visual OCR and degraded inscription analysis.
- **MiMo-V2.5-Pro** is needed for reconstruction reasoning, long-context comparison, and multilingual translation.
- **MiMo-V2.5-Flash** is useful for cheaper metadata extraction, segmentation, and batch quality checks.

The project can route tasks by cost and difficulty: cheaper models for routine batch work, stronger models for high-uncertainty restoration and translation review.

## Auditability and responsible use

KIDAT does not treat AI output as final truth. It stores raw OCR, restored text, translation, confidence, references, and review notes separately. Every reconstruction candidate must include evidence and confidence. Public or scholarly outputs should pass human review.

## Scaling path

1. Run a pilot subset in mock/API mode.
2. Validate schema, prompt quality, and token accounting.
3. Process all 729 slabs through worker queues.
4. Reprocess low-confidence segments with improved prompts and references.
5. Publish searchable, auditable outputs and a public demo.

This creates a credible path from initial testing to large-scale token consumption with measurable cultural heritage outputs.
