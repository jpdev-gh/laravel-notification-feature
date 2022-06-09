<?php

namespace App\Services\Notifications;

use App\Models\User;
use Faker\Generator;
use App\Notifications\SampleOne;
use App\Notifications\SampleTwo;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Notification;

class NotificationTest
{
    public static function sendNotificationsToAllUsers(int $total): void
    {
        for ($item=1; $item <= $total; $item++) {
            $sender = self::createSender();

            Notification::send(
                User::all(), 
                new SampleOne($sender, "SampleOne body $item", 'https://www.google.com/')
            );

            Notification::send(
                User::all(), 
                new SampleTwo($sender, "SampleTwo body $item", 'https://www.google.com/')
            );
        }
    }

    private static function createSender()
    {
        $faker = Container::getInstance()->make(Generator::class);
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $color = trim($faker->hexColor(), '#');

        return (object) [
            'name'  => $firstName.' '.$lastName,
            'photo' => 'https://ui-avatars.com/api/?size=300&bold=true&background='.$color.'&color=ffffff&name='.substr($firstName, 0, 1).substr($lastName, 0, 1).'&bold=true&rounded=true&format=png',
        ];
    }

    public static function markNotificationsAsRead(User $user, int $num): void
    {
        foreach ($user->unreadNotifications()->take($num)->get() as $unreadNotification) {
            $unreadNotification->markAsRead();
        }
    }
}