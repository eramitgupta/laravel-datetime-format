<?php

namespace Tests;

use LaravelDateTimeFormat\DateTimeFormatServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            DateTimeFormatServiceProvider::class,
        ];
    }
}
