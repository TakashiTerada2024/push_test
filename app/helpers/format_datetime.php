<?php

declare(strict_types=1);

use Carbon\Carbon;

if (!function_exists('format_datetime')) {
    function format_datetime($argument, string $default = '', string $format = 'Y-m-d H:i:s'): string
    {
        if (!$argument) {
            return $default;
        }

        if (is_subclass_of($argument, Carbon::class)) {
            /** @var Carbon $argument */
            return $argument->setTimezone('Asia/Tokyo')->format($format);
        }
        $carbon = new Carbon($argument);
        return $carbon->setTimezone('Asia/Tokyo')->format($format);
    }
}
