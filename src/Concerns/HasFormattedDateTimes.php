<?php

namespace LaravelDateTimeFormat\Concerns;

use DateTimeInterface;
use LaravelDateTimeFormat\Formatters\DateTimeFormatter;

trait HasFormattedDateTimes
{
    protected function initializeHasFormattedDateTimes(): void
    {
        if (! config('datetime-format.auto_apply', true)) {
            return;
        }

        foreach ($this->formattedDateAttributes() as $attribute) {
            $this->mergeCasts([$attribute => 'datetime']);
        }
    }

    /**
     * @return array<int, string>
     */
    protected function formattedDateAttributes(): array
    {
        if (property_exists($this, 'formattedDates') && is_array($this->formattedDates) && $this->formattedDates !== []) {
            return array_values(array_filter($this->formattedDates, fn (mixed $value): bool => is_string($value)));
        }

        return method_exists($this, 'getDates')
            ? array_values(array_filter($this->getDates(), fn (mixed $date): bool => is_string($date)))
            : [];
    }

    public function getAttribute($key): mixed
    {
        $value = parent::getAttribute($key);

        if (! is_string($key) || ! in_array($key, $this->formattedDateAttributes(), true)) {
            return $value;
        }

        return app(DateTimeFormatter::class)->format($value);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return (string) app(DateTimeFormatter::class)->format($date);
    }
}
