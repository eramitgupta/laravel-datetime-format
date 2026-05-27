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

it('registers datetime blade directive', function (): void {
    config()->set('datetime-format.format', 'Y-m-d H:i');
    config()->set('datetime-format.timezone', 'UTC');

    $rendered = Blade::render('@dateTimeFormat($value)', [
        'value' => Carbon::parse('2026-05-10 01:02:03', 'UTC'),
    ]);

    expect(trim($rendered))->toBe('2026-05-10 01:02');
});
