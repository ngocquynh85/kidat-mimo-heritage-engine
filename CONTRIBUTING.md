# Contributing to KIDAT

KIDAT is an early-stage research prototype. Contributions that improve
reproducibility, provenance, testing, accessibility, documentation, or safe
human-review boundaries are especially welcome.

## Before opening a pull request

```bash
composer install
vendor/bin/phpunit --configuration phpunit.xml.dist
php bin/kidat demo
php bin/kidat estimate
php scripts/inspect_manifest.php
```

Please keep synthetic/demo outputs clearly labeled. Do not add images,
transcriptions, translations, or other source material unless their
redistribution rights and provenance are documented. Do not include API keys
or private corpus copies in commits.

Pull requests should explain the problem, the behavior changed, and the
verification performed. Changes to the data model or AI output contract
should include a fixture or test.
