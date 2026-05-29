# Reference

## Package surface

Main files:

- `src/Formatters/DateTimeFormatter.php`
- `src/Concerns/HasFormattedDateTimes.php`
- `src/Casts/FormattedDateTimeCast.php`
- `src/DateTimeFormatServiceProvider.php`
- `config/datetime-format.php`

## Formatter rules

`DateTimeFormatter::format()`:

- returns `config('datetime-format.null_value')` for `null`
- accepts `CarbonInterface`, `DateTimeInterface`, strings, and numeric values
- converts parsed values to the resolved timezone
- applies the resolved locale before formatting
- uses the provided format first, otherwise `config('datetime-format.format')`

Helpers:

- `formatDate()` uses `config('datetime-format.date_format')`
- `formatTime()` uses `config('datetime-format.time_format')`

## Trait rules

`HasFormattedDateTimes`:

- auto-merges `datetime` casts for resolved formatted attributes when `datetime-format.auto_apply` is enabled
- formats matching attributes on `getAttribute()`
- formats matching attributes during `attributesToArray()`
- allows three configuration styles:

```php
protected function formattedDateAttributes(): array
{
    return ['updated_at'];
}
```

Uses the global config format.

```php
protected function formattedDateAttributes(): array
{
    return [
        'updated_at' => 'd/m/Y h:i A',
    ];
}
```

Uses a per-attribute format string.

```php
protected array $formattedDate = [
    'updated_at' => ['format' => 'd/m/Y h:i A', 'timezone' => 'Asia/Kolkata'],
];
```

Uses a per-attribute options array and overrides the same field from `formattedDateAttributes()`.

## Service provider additions

The service provider registers:

- container singleton: `LaravelDateTimeFormat\Formatters\DateTimeFormatter`
- container alias: `datetime-formatter`
- Blade directives:
  - `@dateTimeFormat`
  - `@datetime`
  - `@dateFormat`
  - `@timeFormat`
- Carbon macro:
  - `toConfiguredFormat(?string $format = null)`
- `JsonResource::formatDateTime(mixed $value, ?string $format = null)`

It also publishes:

- config with tag `datetime-format-config`
- views with tag `datetime-format-views`

## Test map

Package tests currently cover:

- formatter config behavior
- null handling
- cast formatting
- trait-based model formatting
- serialization formatting
- per-attribute format support
- Blade directives

When adding new behavior, keep examples and tests aligned.
