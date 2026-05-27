<?php

namespace LaravelDateTimeFormat\Facades;

use Illuminate\Support\Facades\Facade;

class DateFormat extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'datetime-formatter';
    }
}
