# Prompt Templates

## OCR / Vision Prompt
You are processing a high-resolution image of a Kuthodaw Pagoda stone inscription. Extract visible text carefully. Preserve uncertainty.

Return JSON:
- visible_text
- uncertain_spans
- damaged_regions
- script_notes
- confidence
- reasoning_summary

Do not invent missing text. Mark unclear regions explicitly.

## Reconstruction Prompt
Given raw OCR, uncertain spans, damaged regions, and reference context, propose restoration candidates.

Return JSON:
- restored_text_candidate
- changed_spans
- evidence
- alternative_candidates
- confidence
- needs_human_review

Do not silently replace uncertain content. Every reconstruction must include evidence and confidence.

## Translation Prompt
Translate the restored inscription text into English and Vietnamese. Preserve Buddhist/Pali technical terminology consistently using the provided glossary.

Return JSON:
- english_translation
- vietnamese_translation
- key_terms
- translation_notes
- confidence
- unresolved_terms

## Review Prompt
Review OCR, restoration, references, and translations. Identify hallucination risk, unsupported restoration, terminology inconsistency, and low-confidence areas.

Return JSON:
- approved
- issues
- recommended_revisions
- confidence
