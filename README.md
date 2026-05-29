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

By default, every formatted date uses the global package config:

- `datetime-format.format`
- `datetime-format.timezone`
- `datetime-format.locale`

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

### 1.1) Different format per attribute 👤

Use `formattedDateAttributes()` when you want different output formats on the same model:

```php
use LaravelDateTimeFormat\Concerns\HasFormattedDateTimes;

class User extends Model
{
    use HasFormattedDateTimes;

    protected function formattedDateAttributes(): array
    {
        return [
            'created_at' => 'd-m-Y',
            'updated_at' => 'd/m/Y h:i A',
            'email_verified_at' => 'M d, Y',
        ];
    }
}
```

Example output:

```json
{
  "created_at": "29-05-2026",
  "updated_at": "29/05/2026 10:30 AM",
  "email_verified_at": "May 29, 2026"
}
```

### 1.2) Different format and timezone per attribute 🌍

Use `$formattedDate` when a field needs additional options like `timezone` or `locale`:

```php
use LaravelDateTimeFormat\Concerns\HasFormattedDateTimes;

class User extends Model
{
    use HasFormattedDateTimes;

    protected array $formattedDate = [
        'created_at' => ['format' => 'd-m-Y', 'timezone' => 'UTC'],
        'updated_at' => ['format' => 'd/m/Y h:i A', 'timezone' => 'Asia/Kolkata'],
        'email_verified_at' => ['format' => 'M d, Y', 'locale' => 'en'],
    ];
}
```

This is enough by itself. You do not need to repeat the same fields in `formattedDateAttributes()`.

Example output:

```json
{
  "created_at": "29-05-2026",
  "updated_at": "29/05/2026 10:30 AM",
  "email_verified_at": "May 29, 2026"
}
```

### 1.3) Real `User.php` example ✅

```php
<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use LaravelDateTimeFormat\Concerns\HasFormattedDateTimes;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasFormattedDateTimes, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    protected array $formattedDate = [
        'updated_at' => ['format' => 'M d, Y'],
    ];
}
```

Rule of thumb:

- use default package config when all date fields should look the same
- use `formattedDateAttributes()` for per-attribute formats
- use `$formattedDate` for per-attribute `format`, `timezone`, and `locale`
- prefer one approach per field to keep model code short and readable

If your custom format is date-only, time will not be included:

```php
protected array $formattedDate = [
    'updated_at' => ['format' => 'M d, Y'],
];
```

Output:

```json
{
  "updated_at": "May 29, 2026"
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
