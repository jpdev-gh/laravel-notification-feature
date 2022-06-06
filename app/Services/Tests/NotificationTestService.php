<?php

namespace App\Services\Tests;

use App\Notifications\SampleNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Container\Container;
use Faker\Generator;

class NotificationTestService
{
    public static function generate(Collection $users, int $total): void
    {
        $faker = Container::getInstance()->make(Generator::class);

        for ($item=0; $item < $total; $item++) { 
            Notification::send(
                $users, 
                new SampleNotification($faker->sentence, 'https://www.google.com/')
            );
        }
    }
}