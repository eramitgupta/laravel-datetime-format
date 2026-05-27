# Contributing

Thank you for contributing to `erag/laravel-datetime-format`.

## Development Setup

```bash
composer install
```

## Code Style

- Follow existing project conventions.
- Run formatter before submitting:

```bash
vendor/bin/pint --dirty --format=agent
```

## Tests

Run package tests before opening a PR:

```bash
php vendor/bin/pest --compact
```

## Pull Request Checklist

- Clear title and description.
- Focused changes (one concern per PR).
- Tests added/updated if behavior changed.
- Formatting and tests pass.
