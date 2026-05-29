# Examples

## Basic model auto format

Start with the smallest setup:

```php
use LaravelDateTimeFormat\Concerns\HasFormattedDateTimes;

class User extends Model
{
    use HasFormattedDateTimes;
}
```

This is enough when your model should use the package defaults for formatted date access and serialization.

Typical response check:

```php
return response()->json([
    'user' => User::first(),
]);
```

With a config like:

```php
'format' => 'd-m-Y H:i:s',
'timezone' => 'Asia/Kolkata',
```

the serialized timestamps follow that config.

## Config-driven model formatting

Use `formattedDateAttributes()` when a field should use the global package config:

```php
use LaravelDateTimeFormat\Concerns\HasFormattedDateTimes;

class User extends Model
{
    use HasFormattedDateTimes;

    protected function formattedDateAttributes(): array
    {
        return [
            'created_at' => 'd-m-Y',
        ];
    }
}
```

If `config/datetime-format.php` contains:

```php
'format' => 'd-m-Y H:i:s',
'timezone' => 'Asia/Kolkata',
```

then:

```php
$user = User::first();

$user->updated_at;
$user->toArray()['updated_at'];
User::all()->toArray()[0]['updated_at'];
```

all resolve to the config-driven formatted value.

## Trait plus collection example

```php
$users = User::get();

return [
    'first_value' => $users->first()?->updated_at,
    'serialized' => $users->toArray(),
];
```

Expected behavior:

- `first_value` is formatted through attribute access
- `serialized[*].updated_at` is formatted during collection serialization

## Per-attribute override with timezone

Use `$formattedDate` when one attribute needs custom options:

```php
use LaravelDateTimeFormat\Concerns\HasFormattedDateTimes;

class User extends Model
{
    use HasFormattedDateTimes;

    protected array $formattedDate = [
        'created_at' => ['format' => 'd-m-Y', 'timezone' => 'UTC'],
        'updated_at' => ['format' => 'd/m/Y h:i A', 'timezone' => 'Asia/Kolkata'],
    ];
}
```

In this case `updated_at` does not use `config('datetime-format.format')`; the attribute override wins.

## Custom cast for one field

```php
use LaravelDateTimeFormat\Casts\FormattedDateTimeCast;

protected function casts(): array
{
    return [
        'email_verified_at' => FormattedDateTimeCast::class,
    ];
}
```

## Blade usage

See [core.blade.php](core.blade.php) for a minimal template. Typical direct usage:

```blade
@datetime($user->updated_at)
@dateFormat($user->created_at)
@timeFormat($lastLoginAt)
```

## Controller response example

This mirrors the common check developers use while integrating the package:

```php
return response()->json([
    'user_find' => User::find(1),
    'user' => User::get(),
    'date' => User::find(1)?->updated_at,
    'user_array' => User::first()?->toArray(),
]);
```

Expected behavior:

- `date` is formatted through attribute access
- `user_array.updated_at` is formatted through serialization
- `user` collection items are formatted when serialized to JSON
- raw database storage is unchanged
