<?php

namespace LaravelDateTimeFormat\Commands;

use Illuminate\Console\Command;

class InstallDateTimeFormatCommand extends Command
{
    protected $signature = 'erag:install-datetime-format';

    protected $description = 'Publish the DateTime format package configuration';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--provider' => 'LaravelDateTimeFormat\\DateTimeFormatServiceProvider',
            '--tag' => 'datetime-format-config',
            '--force' => false,
        ]);

        $this->components->info('DateTime format package installed successfully.');

        return self::SUCCESS;
    }
}
