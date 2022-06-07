<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Notifications\SampleNotification;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Notification;
use App\Services\Tests\NotificationTestService;
use App\Services\Notifications\NotificationType;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationTest extends TestCase
{   
    use DatabaseTransactions;

    // filterByReadAt
    // filterByDateRange
    // validation for mark_as_read
    // MultipleFilter depends on needs

    /**
     * @test
     */
    public function fetch_recent_notifications()
    {
        $this->withoutExceptionHandling();

        User::factory()->count(2)->create();
 
        NotificationTestService::generateNotificationsToAllUsers(15);

        $response = $this->actingAs(User::first())->getJson('/notifications');

        $response
            ->assertOk()
            ->assertJsonCount(20, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'title',
                        'body',
                        'click_action',
                        'sender' => [
                            'name',
                            'photo',
                        ],
                        'read_at' => [
                            'self',
                            'dfh',
                        ],
                        'created_at' => [
                            'self',
                            'fdy',
                        ],
                        'updated_at',
                    ]
                ]
            ])
            ->assertJsonFragment([
                'title' => 'Sample One',
            ])
            ->assertJsonFragment([
                'title' => 'Sample Two',
            ])
            ->assertJsonMissing([
                'title' => 'Sample Three',
            ]);
    }

    /**
     * @test
     */
    public function fetch_4_recent_notifications()
    {
        $this->withoutExceptionHandling();

        User::factory()->count(2)->create();
 
        NotificationTestService::generateNotificationsToAllUsers(3);

        $response = $this->actingAs(User::first())->getJson('/notifications?take=4');

        $response
            ->assertOk()
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'title',
                        'body',
                        'click_action',
                        'sender' => [
                            'name',
                            'photo',
                        ],
                        'read_at' => [
                            'self',
                            'dfh',
                        ],
                        'created_at' => [
                            'self',
                            'fdy',
                        ],
                        'updated_at',
                    ]
                ]
            ])
            ->assertJsonFragment([
                'title' => 'Sample One',
            ])
            ->assertJsonFragment([
                'title' => 'Sample Two',
            ])
            ->assertJsonMissing([
                'title' => 'Sample Three',
            ]);
    }

    /**
     * @test
     */
    public function fetch_notifications_by_type()
    {
        $this->withoutExceptionHandling();

        User::factory()->count(2)->create();
 
        NotificationTestService::generateNotificationsToAllUsers(2);

        $type = array_rand(NotificationType::all(), 1);

        $response = $this->actingAs(User::first())->getJson('/notifications?&type='.$type);

        $response
            ->assertOk()
            ->assertJsonFragment(['type' => NotificationType::get($type)]);

        $this->assertEquals(NotificationType::get($type), $response->json()['data'][0]['type']);
    }    

    /**
     * @test
     */
    public function fetch_unread_notifications_count()
    {
        $this->withoutExceptionHandling();

        User::factory()->count(1)->create();
        
        NotificationTestService::generateNotificationsToAllUsers(5);

        $user = User::first();

        foreach ($user->unreadNotifications()->take(3)->get() as $unreadNotification) {
            $unreadNotification->markAsRead();
        }

        $response = $this->actingAs($user)->getJson('/notifications/count');

        $response
            ->assertOk()
            ->assertExactJson(["count" => 7]);

        $this->assertEquals(7, $response->json()['count']);
    }

    /**
     * @test
     */
    public function single_notification_can_be_created()
    {
        User::factory()->count(2)->create();

        NotificationTestService::generateNotificationsToAllUsers(4);
        
        $this->assertEquals(8, count(User::first()->notifications));
    }    

    /**
     * @test
     */
    public function single_notification_can_be_mark_as_read()
    {
        $this->withoutExceptionHandling();

        $users = User::factory()->count(2)->create();

        NotificationTestService::generateNotificationsToAllUsers(3);
        
        $user = User::first();

        $notificationId = $user->notifications()->inRandomOrder()->first()->id;

        $readDate = Carbon::now()->subHours(5);

        $response = $this->actingAs($user)->patchJson('/notifications/'.$notificationId, [
            'read_at' => $readDate,
        ]);

        $response
            ->assertSuccessful()
            ->assertJsonPath('data.read_at.dfh', '5 hours before')
            ->assertJsonPath('data.read_at.self', (string) $readDate)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'title',
                    'body',
                    'click_action',
                    'sender' => [
                        'name',
                        'photo',
                    ],
                    'read_at' => [
                        'self',
                        'dfh',
                    ],
                    'created_at' => [
                        'self',
                        'fdy',
                    ],
                    'updated_at',
                ]
            ]);

    }
}
