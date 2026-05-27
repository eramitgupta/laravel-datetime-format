<?php

namespace LaravelDateTimeFormat\Formatters;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Carbon;

class DateTimeFormatter
{
    public function format(
        mixed $value,
        ?string $format = null,
        ?string $timezone = null,
        ?string $locale = null,
    ): mixed {
        if ($value === null) {
            return config('datetime-format.null_value');
        }

        $carbon = $this->toCarbon($value);

        if ($carbon === null) {
            return $value;
        }

        $resolvedTimezone = $timezone ?? (string) config('datetime-format.timezone', 'UTC');
        $resolvedLocale = $locale ?? (string) config('datetime-format.locale', 'en');
        $resolvedFormat = $format ?? (string) config('datetime-format.format', 'Y-m-d H:i:s');

        return $carbon
            ->copy()
            ->setTimezone($resolvedTimezone)
            ->locale($resolvedLocale)
            ->format($resolvedFormat);
    }

    public function formatDate(mixed $value): mixed
    {
        return $this->format($value, (string) config('datetime-format.date_format', 'Y-m-d'));
    }

    public function formatTime(mixed $value): mixed
    {
        return $this->format($value, (string) config('datetime-format.time_format', 'H:i:s'));
    }

    private function toCarbon(mixed $value): ?CarbonInterface
    {
        if ($value instanceof CarbonInterface) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return Carbon::instance($value);
        }

        if (is_string($value) || is_numeric($value)) {
            try {
                return Carbon::parse((string) $value);
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }
}
