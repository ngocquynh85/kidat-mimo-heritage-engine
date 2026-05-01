# Data Sources

KIDAT does not redistribute the full Kuthodaw image corpus in this repository.

Instead, this directory contains a source manifest that points to an existing public GitHub repository:

- Upstream: <https://github.com/kit119/KIT-729>
- Description: Kuthodaw Inscription Tipitaka viewer with high-resolution `.webp` images
- Local manifest: `data/upstream_kit729_manifest.jsonl`

## Why the images are not copied here

The upstream repository currently does not declare a machine-readable license, and its README asks users to verify image source licensing before redistribution. To avoid accidental redistribution or ownership confusion, KIDAT stores only metadata and source URLs.

During real processing, workers can read from approved local copies or fetch from source URLs after licensing and permission checks are complete.

## Manifest fields

Each JSONL row includes:

- `source`
- `source_url`
- `license_status`
- `upstream_path`
- `raw_url`
- `file_name`
- `slab_code`
- `byte_size`
- `sha`
- `manifest_index`

## Current upstream inventory

The current manifest references 734 `.webp` files under `images/clean/`, totaling about 804 MB upstream. This includes standard slab numbers and special prefatory/cover images such as `00A`–`00D`.
