# Laravel DateTime Format

A powerful Laravel package for centralized and consistent date/time formatting across your application. Automatically format Eloquent model dates, API responses, Blade output, and Carbon instances without repeating manual ->format(...) calls everywhere.
## Key Features 🔥


* 👤 Automatic Eloquent model datetime formatting
* 🧠 Centralized datetime formatter service
* 🧩 Custom cast support with `FormattedDateTimeCast`
* 🎨 Blade directive support via `@dateTimeFormat(...)`, `@datetime(...)`, `@dateFormat(...)`, and `@timeFormat(...)`
* ⏱️ Carbon macro support using `toConfiguredFormat()`
* 📦 API resource helper macros
* 🌍 Timezone and locale support
* 🧱 Laravel `10`, `11`, `12`, and `13` support
* ✅ PHP `8.2+` support

## Install 🚀

```bash
composer require erag/laravel-datetime-format
php artisan erag:install-datetime-format
```

## Config ⚙️

Published file: `config/datetime-format.php`

```php
return [
    'format' => 'd-m-Y H:i:s',
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'locale' => env('APP_LOCALE', 'en'),
    'null_value' => null,
    'auto_apply' => true,
    'date_format' => 'd-m-Y',
    'time_format' => 'H:i:s',
];
```

### What `timezone` and `locale` do 🌍

- `timezone`: defines the timezone used for formatted output.  
  Example: the input can be UTC, but output can be converted to `Asia/Kolkata`.
- `locale`: sets Carbon’s language/context before formatting.  
  This is useful when using month/day names, such as `28 May 2026` or localized month labels.

## Quick Understanding (Before vs After) 👀

Without package (common output):

```json
{
  "created_at": "2026-05-27T15:39:13.000000Z"
}
```

With package + trait:

```json
{
  "created_at": "27-05-2026 21:09:13"
}
```

## Usage

### 1) Model Auto Format (Recommended) 👤

```php
use LaravelDateTimeFormat\Concerns\HasFormattedDateTimes;

class User extends Model
{
    use HasFormattedDateTimes;
}
```

Controller:

```php
return response()->json([
    'user' => User::first(),
]);
```

Response example:

```json
{
  "user": {
    "id": 1,
    "name": "Kaden Herring",
    "email": "biwepa@mailinator.com",
    "created_at": "27-05-2026 21:09:13",
    "updated_at": "27-05-2026 21:09:13"
  }
}
```

### 2) Custom Cast (When you only want specific columns formatted) 🧩

```php
use LaravelDateTimeFormat\Casts\FormattedDateTimeCast;

protected function casts(): array
{
    return [
        'email_verified_at' => FormattedDateTimeCast::class,
    ];
}
```

Example output:

```json
{
  "email_verified_at": "28-05-2026 15:45:30"
}
```

### 3) Blade Directive 🎨

```blade
@dateTimeFormat($user->created_at)
@datetime($user->created_at)
@dateFormat($user->created_at)
@timeFormat($user->created_at)
```

Rendered output:

```text
27-05-2026 21:09:13
27-05-2026 21:09:13
27-05-2026
21:09:13
```

### 4) Formatter Service 🧠

```php
use LaravelDateTimeFormat\Formatters\DateTimeFormatter;

public function show(DateTimeFormatter $formatter)
{
    return [
        'datetime' => $formatter->format('2026-05-28 10:15:30'),
        'date' => $formatter->formatDate('2026-05-28 10:15:30'),
        'time' => $formatter->formatTime('2026-05-28 10:15:30'),
    ];
}
```

Response example:

```json
{
  "datetime": "28-05-2026 15:45:30",
  "date": "28-05-2026",
  "time": "15:45:30"
}
```

### 5) Facade Usage 🛠️

```php
use DateFormat;

DateFormat::format(now());
DateFormat::format(now(), 'd/m/Y H:i');
```

### 6) Carbon Macro ⏱️

```php
Carbon::now()->toConfiguredFormat();
Carbon::now()->toConfiguredFormat('d M Y, h:i A');
```

### 7) API Resource Macro 📦

```php
return [
    'created_at' => $this->formatDateTime($this->created_at),
];
```

Resource output example:

```json
{
  "created_at": "27-05-2026 21:09:13"
}
```

## Real Demo Style Response ✅

If you want to see mixed output (service + blade + model):

```json
{
  "source_utc": "2026-05-28 10:15:30 UTC",
  "formatter_service": "28-05-2026 15:45:30",
  "date_only": "2026-05-28",
  "time_only": "15:45:30",
  "facade": "28/05/2026 15:45",
  "carbon_macro": "28 May 2026, 03:45 PM",
  "blade_directive": "28-05-2026 15:45:30",
  "user_date": {
    "data": [
      {
        "created_at": "27-05-2026 21:09:13",
        "updated_at": "27-05-2026 21:09:13"
      }
    ]
  }
}
```

## Service Provider Discovery 🔍

The package uses Composer auto-discovery, so manual service provider registration is usually not required.

## Practical Integration Flow

1. Publish the config.
2. Set global format/timezone.
3. Add `HasFormattedDateTimes` to your models.
4. Use directive/macro in Blade and API resources.
5. Keep controllers lean and let the package handle formatting.
