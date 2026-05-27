<?php

use Illuminate\Support\Carbon;
use LaravelDateTimeFormat\Formatters\DateTimeFormatter;

it('formats a datetime using package config', function (): void {
    config()->set('datetime-format.format', 'Y-m-d H:i');
    config()->set('datetime-format.timezone', 'Asia/Kolkata');

    $formatted = app(DateTimeFormatter::class)->format(Carbon::parse('2026-01-01 10:00:00', 'UTC'));

    expect($formatted)->toBe('2026-01-01 15:30');
});

it('returns configured null value for null input', function (): void {
    config()->set('datetime-format.null_value', 'N/A');

    $formatted = app(DateTimeFormatter::class)->format(null);

    expect($formatted)->toBe('N/A');
});
