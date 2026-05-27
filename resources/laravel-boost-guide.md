# Laravel Boost Guide

This guide explains how to work on `erag/laravel-datetime-format` efficiently with Laravel Boost.

## 1) Check App / Package Context First

Use Boost app info before changes:

- `application-info`
- `search-docs` (required before implementation changes)

For this package, focus docs around:

- Service providers
- Eloquent custom casts
- Blade custom directives
- Carbon date handling

## 2) Recommended Dev Flow

1. Search version-specific docs with `search-docs`.
2. Implement changes in:
   - `src/Formatters/DateTimeFormatter.php`
   - `src/Casts/FormattedDateTimeCast.php`
   - `src/Concerns/HasFormattedDateTimes.php`
   - `src/DateTimeFormatServiceProvider.php`
3. Run formatter:
   - `vendor/bin/pint --dirty --format=agent`
4. Run tests:
   - `php vendor/bin/pest --compact`

## 3) Testing Scope

Prefer targeted tests:

- `tests/Unit/DateTimeFormatterTest.php`
- `tests/Feature/ServiceProviderFeaturesTest.php`

Only run full suite when needed.

## 4) CI Expectations

Workflow should run:

- `composer lint`
- `composer test`

If `composer install` fails in CI, verify PHP version compatibility with `composer.lock`.

## 5) Common Pitfalls

- Namespace mismatch between package and app imports.
- Provider class mismatch in `bootstrap/providers.php`.
- Blade directive rename not reflected in tests/README.
- Lock file generated on higher PHP than CI runtime.

## 6) Package Resources

`resources/views` is loaded by service provider using:

- `loadViewsFrom(__DIR__.'/../resources/views', 'datetime-format')`
- publish tag: `datetime-format-views`

So Blade overrides can be published to:

- `resources/views/vendor/datetime-format`
