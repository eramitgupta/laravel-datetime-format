<?php

namespace LaravelDateTimeFormat\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use LaravelDateTimeFormat\Formatters\DateTimeFormatter;

class FormattedDateTimeCast implements CastsAttributes
{
    public function __construct(
        protected ?string $format = null,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(
        Model $model,
        string $key,
        mixed $value,
        array $attributes,
    ): mixed {
        return app(DateTimeFormatter::class)->format($value, $this->format);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(
        Model $model,
        string $key,
        mixed $value,
        array $attributes,
    ): mixed {
        return $value;
    }
}
