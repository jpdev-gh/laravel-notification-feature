<?php

namespace App\Services\Notifications;

class NotificationType 
{
    private static $type = [
        'sample_one' => 'App\Notifications\SampleOne',
        'sample_two' => 'App\Notifications\SampleTwo',
    ];

    public static function get($key)
    {
        return self::$type[$key];
    }

    public static function all()
    {
        return self::$type;
    }
}