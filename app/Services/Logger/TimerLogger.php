<?php

namespace App\Services\Logger;

use Illuminate\Support\Facades\Log;

class TimerLogger
{
    public static function info(string $message, array $context = [])
    {
        Log::channel('timer')->info($message, $context);
    }

    public static function warning(string $message, array $context = [])
    {
        Log::channel('timer')->warning($message, $context);
    }

    public static function error(string $message, array $context = [])
    {
        Log::channel('timer')->error($message, $context);
    }
}
