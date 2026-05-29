<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use LaravelDateTimeFormat\Casts\FormattedDateTimeCast;
use LaravelDateTimeFormat\Concerns\HasFormattedDateTimes;

it('formats value through custom cast', function (): void {
    config()->set('datetime-format.format', 'Y-m-d H:i:s');
    config()->set('datetime-format.timezone', 'UTC');

    $model = new class extends Model
    {
        protected $guarded = [];

        protected function casts(): array
        {
            return [
                'starts_at' => FormattedDateTimeCast::class,
            ];
        }
    };

    $model->setRawAttributes([
        'starts_at' => '2026-04-01 12:34:56',
    ]);

    expect($model->starts_at)->toBe('2026-04-01 12:34:56');
});

it('formats model date attributes through trait during attribute access', function (): void {
    config()->set('datetime-format.format', 'Y-m-d');

    $model = new class extends Model
    {
        use HasFormattedDateTimes;

        protected $guarded = [];

        protected function formattedDateAttributes(): array
        {
            return ['created_at'];
        }

        protected function casts(): array
        {
            return [
                'created_at' => 'datetime',
            ];
        }
    };

    $model->setRawAttributes([
        'created_at' => '2026-05-01 10:11:12',
    ]);

    expect($model->created_at)->toBe('2026-05-01');
});

it('formats model dates in serialized array output', function (): void {
    config()->set('datetime-format.format', 'Y-m-d H:i:s');
    config()->set('datetime-format.timezone', 'UTC');

    $model = new class extends Model
    {
        use HasFormattedDateTimes;

        protected $guarded = [];

        protected function formattedDateAttributes(): array
        {
            return ['created_at'];
        }

        protected function casts(): array
        {
            return [
                'created_at' => 'datetime',
            ];
        }
    };

    $model->setRawAttributes([
        'created_at' => '2026-05-01 10:11:12',
    ]);

    expect($model->toArray()['created_at'])->toBe('2026-05-01 10:11:12');
});

it('supports per-attribute formats through formattedDateAttributes', function (): void {
    config()->set('datetime-format.format', 'Y-m-d H:i:s');
    config()->set('datetime-format.timezone', 'UTC');

    $model = new class extends Model
    {
        use HasFormattedDateTimes;

        protected $guarded = [];

        protected function formattedDateAttributes(): array
        {
            return [
                'created_at' => 'd-m-Y',
                'updated_at' => 'd/m/Y h:i A',
                'email_verified_at' => 'M d, Y',
            ];
        }
    };

    $model->setRawAttributes([
        'created_at' => '2026-05-01 10:11:12',
        'updated_at' => '2026-05-02 15:16:17',
        'email_verified_at' => '2026-05-03 20:21:22',
    ]);

    expect($model->created_at)->toBe('01-05-2026')
        ->and($model->updated_at)->toBe('02/05/2026 03:16 PM')
        ->and($model->email_verified_at)->toBe('May 03, 2026');
});

it('does not include time when a custom formattedDate uses a date-only format', function (): void {
    config()->set('datetime-format.format', 'Y-m-d H:i:s');
    config()->set('datetime-format.timezone', 'UTC');

    $model = new class extends Model
    {
        use HasFormattedDateTimes;

        protected $guarded = [];

        protected array $formattedDate = [
            'updated_at' => ['format' => 'M d, Y'],
        ];
    };

    $model->setRawAttributes([
        'updated_at' => '2026-05-01 10:11:12',
    ]);

    expect($model->updated_at)->toBe('May 01, 2026')
        ->and($model->toArray()['updated_at'])->toBe('May 01, 2026');
});

it('registers datetime blade directive', function (): void {
    config()->set('datetime-format.format', 'Y-m-d H:i');
    config()->set('datetime-format.timezone', 'UTC');

    $rendered = Blade::render('@dateTimeFormat($value)', [
        'value' => Carbon::parse('2026-05-10 01:02:03', 'UTC'),
    ]);

    expect(trim($rendered))->toBe('2026-05-10 01:02');
});

it('registers date format blade directive', function (): void {
    config()->set('datetime-format.date_format', 'd/m/Y');
    config()->set('datetime-format.timezone', 'UTC');

    $rendered = Blade::render('@dateFormat($value)', [
        'value' => Carbon::parse('2026-05-10 01:02:03', 'UTC'),
    ]);

    expect(trim($rendered))->toBe('10/05/2026');
});

it('registers time format blade directive', function (): void {
    config()->set('datetime-format.time_format', 'h:i A');
    config()->set('datetime-format.timezone', 'UTC');

    $rendered = Blade::render('@timeFormat($value)', [
        'value' => Carbon::parse('2026-05-10 01:02:03', 'UTC'),
    ]);

    expect(trim($rendered))->toBe('01:02 AM');
});
