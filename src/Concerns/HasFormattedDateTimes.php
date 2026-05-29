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

        foreach (array_keys($this->resolvedFormattedDateAttributes()) as $attribute) {
            $this->mergeCasts([$attribute => 'datetime']);
        }
    }

    /**
     * @return array<int, string>|array<string, string|array{format?: string, timezone?: string, locale?: string}>
     */
    protected function formattedDateAttributes(): array
    {
        if (property_exists($this, 'formattedDates') && is_array($this->formattedDates) && $this->formattedDates !== []) {
            return $this->formattedDates;
        }

        return method_exists($this, 'getDates')
            ? array_values(array_filter($this->getDates(), fn (mixed $date): bool => is_string($date)))
            : [];
    }

    public function getAttribute($key): mixed
    {
        $value = parent::getAttribute($key);
        $formattedAttributes = $this->resolvedFormattedDateAttributes();

        if (! is_string($key) || ! array_key_exists($key, $formattedAttributes)) {
            return $value;
        }

        return $this->formatDateAttributeValue($value, $formattedAttributes[$key]);
    }

    /**
     * @return array<string, mixed>
     */
    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();
        $formattedAttributes = $this->resolvedFormattedDateAttributes();

        foreach ($formattedAttributes as $attribute => $options) {
            if (! array_key_exists($attribute, $attributes) && ! array_key_exists($attribute, $this->getAttributes())) {
                continue;
            }

            $attributes[$attribute] = $this->formatDateAttributeValue(parent::getAttribute($attribute), $options);
        }

        return $attributes;
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return (string) app(DateTimeFormatter::class)->format($date);
    }

    /**
     * @return array<string, array{format?: string, timezone?: string, locale?: string}>
     */
    protected function resolvedFormattedDateAttributes(): array
    {
        $attributes = $this->normalizeFormattedDateAttributes($this->formattedDateAttributes());

        if (property_exists($this, 'formattedDate') && isset($this->formattedDate)) {
            foreach ($this->normalizeFormattedDateAttributes($this->formattedDate) as $attribute => $options) {
                $attributes[$attribute] = array_merge($attributes[$attribute] ?? [], $options);
            }
        }

        if (property_exists($this, 'formattedDateFormats') && is_array($this->formattedDateFormats)) {
            foreach ($this->normalizeFormattedDateAttributes($this->formattedDateFormats) as $attribute => $options) {
                $attributes[$attribute] = array_merge($attributes[$attribute] ?? [], $options);
            }
        }

        return $attributes;
    }

    /**
     * @param  array<int, string>|array<string, string|array{format?: string, timezone?: string, locale?: string}>  $attributes
     * @return array<string, array{format?: string, timezone?: string, locale?: string}>
     */
    protected function normalizeFormattedDateAttributes(array $attributes): array
    {
        $normalized = [];

        foreach ($attributes as $attribute => $options) {
            if (is_int($attribute) && is_string($options)) {
                $normalized[$options] = [];

                continue;
            }

            if (! is_string($attribute)) {
                continue;
            }

            if (is_string($options)) {
                $normalized[$attribute] = ['format' => $options];

                continue;
            }

            if (! is_array($options)) {
                continue;
            }

            $normalized[$attribute] = array_filter([
                'format' => isset($options['format']) && is_string($options['format']) ? $options['format'] : null,
                'timezone' => isset($options['timezone']) && is_string($options['timezone']) ? $options['timezone'] : null,
                'locale' => isset($options['locale']) && is_string($options['locale']) ? $options['locale'] : null,
            ], fn (mixed $value): bool => $value !== null);
        }

        return $normalized;
    }

    /**
     * @param  array{format?: string, timezone?: string, locale?: string}  $options
     */
    protected function formatDateAttributeValue(mixed $value, array $options): mixed
    {
        return app(DateTimeFormatter::class)->format(
            $value,
            $options['format'] ?? null,
            $options['timezone'] ?? null,
            $options['locale'] ?? null,
        );
    }
}
