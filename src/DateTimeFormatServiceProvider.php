<?php

namespace LaravelDateTimeFormat;

use Carbon\CarbonInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use LaravelDateTimeFormat\Commands\InstallDateTimeFormatCommand;
use LaravelDateTimeFormat\Formatters\DateTimeFormatter;

class DateTimeFormatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/datetime-format.php', 'datetime-format');

        $this->app->singleton(DateTimeFormatter::class, fn (): DateTimeFormatter => new DateTimeFormatter);
        $this->app->alias(DateTimeFormatter::class, 'datetime-formatter');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'datetime-format');

        $this->publishes([
            __DIR__.'/../config/datetime-format.php' => config_path('datetime-format.php'),
        ], 'datetime-format-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/datetime-format'),
        ], 'datetime-format-views');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallDateTimeFormatCommand::class,
            ]);
        }

        Carbon::macro('toConfiguredFormat', function (?string $format = null): string {
            /** @var CarbonInterface $this */
            return app(DateTimeFormatter::class)->format($this, $format);
        });

        Blade::directive('dateTimeFormat', function (string $expression): string {
            return "<?php echo app('datetime-formatter')->format({$expression}); ?>";
        });

        Blade::directive('datetime', function (string $expression): string {
            return "<?php echo app('datetime-formatter')->format({$expression}); ?>";
        });

        JsonResource::macro('formatDateTime', function (mixed $value, ?string $format = null): mixed {
            return app(DateTimeFormatter::class)->format($value, $format);
        });
    }
}
