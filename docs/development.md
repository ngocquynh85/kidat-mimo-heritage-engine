# Development

## Install

```bash
composer install
cp .env.example .env
```

## Run mock demo

Composer is recommended, but the CLI includes a small fallback autoloader so the mock demo can run before dependencies are installed.

```bash
php bin/kidat demo
php bin/kidat estimate
```



```bash
composer demo
# or
php bin/kidat demo
```

By default `KIDAT_MIMO_MOCK=true`, so the demo does not call the real MiMo API.

## Estimate token demand

```bash
composer estimate
```

## Real API mode

When MiMo API credentials and endpoint details are available:

```bash
KIDAT_MIMO_MOCK=false
KIDAT_MIMO_API_KEY=...
KIDAT_MIMO_BASE_URL=...
```

The current client uses an OpenAI-compatible `/chat/completions` placeholder and may need adjustment to the final MiMo API spec.
