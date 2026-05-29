---
name: laravel-datetime-format
description: "Activate when the user is adding, debugging, or documenting centralized date and time formatting with erag/laravel-datetime-format. Use for DateTimeFormatter usage, HasFormattedDateTimes, formattedDateAttributes(), per-attribute $formattedDate overrides, FormattedDateTimeCast, Blade directives like @datetime and @dateFormat, Carbon toConfiguredFormat(), JsonResource formatDateTime(), config/datetime-format.php, install command wiring, service provider registration, published views, or tests for model and serialization formatting."
license: MIT
metadata:
  author: laravel
---

# Laravel DateTime Format

Use this skill when a Laravel task involves `erag/laravel-datetime-format`.

## Documentation

Use `search-docs` first when it is available for Laravel integration details. For package-specific behavior, inspect:

- `src/Formatters/DateTimeFormatter.php`
- `src/Concerns/HasFormattedDateTimes.php`
- `src/Casts/FormattedDateTimeCast.php`
- `src/DateTimeFormatServiceProvider.php`
- `config/datetime-format.php`
- `tests/Feature/*`
- `tests/Unit/*`
- `references/references.md`
- `references/examples.md`
- `references/core.blade.php`

Load only the reference file you need:

- `references/references.md` for package surface and behavior rules
- `references/examples.md` for real usage snippets
- `references/core.blade.php` for Blade directive examples

## Core Working Pattern

1. Check `config/datetime-format.php` first. The global output format, timezone, locale, and null handling live there.
2. Use `DateTimeFormatter` when formatting ad hoc values in services, controllers, resources, or support code.
3. Use `HasFormattedDateTimes` on Eloquent models when model attributes should format automatically during attribute access and serialization.
4. Use `formattedDateAttributes()` when a model should opt specific attributes into the global package config format.
5. Use `$formattedDate` when a specific attribute needs its own `format`, `timezone`, or `locale`.
6. Use `FormattedDateTimeCast` when only one or two columns need formatting and a trait on the whole model would be too broad.
7. For Blade output, prefer `@datetime`, `@dateTimeFormat`, `@dateFormat`, or `@timeFormat` instead of repeating `->format(...)`.

## Important Behavior

- `DateTimeFormatter::format()` falls back to `config('datetime-format.format')`, `timezone`, and `locale`.
- `formattedDateAttributes()` can return a plain list like `['updated_at']`. In that form, those attributes use the global config format.
- `formattedDateAttributes()` can also return keyed formats like `['updated_at' => 'd/m/Y h:i A']`.
- `$formattedDate` overrides `formattedDateAttributes()` per attribute and supports `format`, `timezone`, and `locale`.
- `HasFormattedDateTimes` affects attribute access and `toArray()` / JSON serialization. It does not rewrite raw database values stored inside the model.
- `FormattedDateTimeCast` formats on read and leaves the stored value unchanged on write.
- The service provider registers:
  - Blade directives: `@dateTimeFormat`, `@datetime`, `@dateFormat`, `@timeFormat`
  - Carbon macro: `toConfiguredFormat()`
  - JsonResource macro: `formatDateTime()`
  - publish tags: `datetime-format-config` and `datetime-format-views`

## Verification

Prefer targeted tests:

- `php vendor/bin/pest --compact tests/Unit/DateTimeFormatterTest.php`
- `php vendor/bin/pest --compact tests/Feature/ServiceProviderFeaturesTest.php`

When model formatting behavior changes in the host app, also run the app tests that cover:

- attribute access
- `toArray()`
- collection serialization
- per-attribute overrides

## Common Pitfalls

- Expecting raw model internals shown by a dumper to be rewritten when the package only formats access and serialization paths
- Mixing `formattedDateAttributes()` and `$formattedDate` for the same field without realizing `$formattedDate` wins
- Forgetting that `formattedDateAttributes()` with a plain list uses the config `format`
- Using a date-only custom format and then expecting time to remain visible
- Changing Blade directives or provider bindings without updating tests and examples
