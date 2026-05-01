# Decisions

## D-001 — Positioning
The project is presented as a prototype/pilot for an AI-assisted cultural heritage workflow, not as a completed production archive.

## D-002 — MiMo fit
MiMo-V2.5-Omni is positioned for image/OCR and visual reconstruction; MiMo-V2.5-Pro for reasoning, cross-reference, and translation; MiMo-V2.5-Flash for batch metadata and cheap validation.

## D-003 — Stack
Initial scaffold: PHP 8.2+, MySQL 8, Ubuntu worker model, Supervisor-compatible long-running workers. Optional Qdrant/Neo4j remains future extension.

## D-004 — Auditability
Raw OCR, restored text, translations, confidence scores, and reviewer notes must be versioned separately.
