<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Notifications\SampleNotification;
use App\Services\Tests\NotificationTestService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationTest extends TestCase
{   
    use DatabaseTransactions;

    public function test_notifications_can_be_created()
    {
        $users = User::factory()->count(5)->create();

        NotificationTestService::generate($users, 10);
        
        $user = User::first();

        $this->assertEquals(10, count($user->notifications));
    }
    
    // public function test_fetch_4_recent_notifications()
    // {
    //     $this->withoutExceptionHandling();

    //     $users = User::factory()->count(10)->create();

    //     $generatedNotificationCount = 20;

    //     NotificationTestService::generate($users, $generatedNotificationCount);

    //     $user = User::first();

    //     $response = $this->actingAs($user)->getJson('/notifications');

    //     // $response->assertOk();

    //     // $this->assertEquals(20, count($response->json()));

    //     // $response->json();
    // }
}
