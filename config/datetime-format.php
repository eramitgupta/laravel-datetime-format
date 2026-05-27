<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default DateTime Format
    |--------------------------------------------------------------------------
    |
    | This format is used as the default output format for date-time values
    | when no explicit format is provided to the formatter.
    |
    */
    'format' => 'Y-m-d H:i:s',

    /*
    |--------------------------------------------------------------------------
    | Output Timezone
    |--------------------------------------------------------------------------
    |
    | All date-time values are converted to this timezone before formatting.
    | Typically this should match your application's display timezone.
    |
    */
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Output Locale
    |--------------------------------------------------------------------------
    |
    | The locale is applied to Carbon instances before formatting. This is
    | useful when you are using localized date output patterns.
    |
    */
    'locale' => env('APP_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Null Value Placeholder
    |--------------------------------------------------------------------------
    |
    | Defines the value returned when the incoming date value is null.
    | Set this to a string such as "N/A" if needed.
    |
    */
    'null_value' => null,

    /*
    |--------------------------------------------------------------------------
    | Auto Apply Date Casts
    |--------------------------------------------------------------------------
    |
    | When enabled, the HasFormattedDateTimes trait will merge datetime casts for
    | configured attributes so they are handled consistently.
    |
    */
    'auto_apply' => true,

    /*
    |--------------------------------------------------------------------------
    | Date-only Format
    |--------------------------------------------------------------------------
    |
    | This format is used by the formatDate() helper for date-only output.
    |
    */
    'date_format' => 'Y-m-d',

    /*
    |--------------------------------------------------------------------------
    | Time-only Format
    |--------------------------------------------------------------------------
    |
    | This format is used by the formatTime() helper for time-only output.
    |
    */
    'time_format' => 'H:i:s',
];
