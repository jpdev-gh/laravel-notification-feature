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

    // filterByDateRange - assert if 20, assert date 
    // validation for single_notification_can_be_mark_as_read
    // MultipleFilter depends on needs
    // write the tests depends on the client needs
    // dont fcking write the tests on every fcking situations
    // add try catch to create, update, delete
    // check filter if not supported return all as default

    /**
     * @test
     */
    public function fetch_recent_notifications()
    {
        $this->withoutExceptionHandling();

        User::factory()->count(2)->create();

        NotificationTestService::generateNotificationsToAllUsers(12);

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
    public function fetch_unread_notifications()
    {
        $this->withoutExceptionHandling();

        User::factory()->count(1)->create();
        
        NotificationTestService::generateNotificationsToAllUsers(5); 

        $user = User::first();

        foreach ($user->unreadNotifications()->take(3)->get() as $unreadNotification) {
            $unreadNotification->markAsRead();
        }

        $response = $this->actingAs($user)->getJson('/notifications?read_at=unread');

        $response
            ->assertOk()
            ->assertJsonCount(7, 'data')
            ->assertJsonPath('data.read_at.self', null)
            ->assertJsonPath('data.read_at.dfh', null)
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
            ]);


        $this->assertNull($response->json()['data'][0]['read_at']['self']);
        
    }

    /**
     * @test
     */
    public function fetch_read_notifications()
    {
        $this->withoutExceptionHandling();

        User::factory()->count(1)->create();
        
        NotificationTestService::generateNotificationsToAllUsers(5); 

        $user = User::first();

        NotificationTestService::markNotificationAsRead($user, 3);

        $response = $this->actingAs($user)->getJson('/notifications?read_at=read');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonMissingExact(['read_at' => [
                'self' => null,
                'dfh' => null,
            ]])
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
            ]);

        $this->assertIsString($response->json()['data'][0]['read_at']['self']);
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

        $unreadNotifications = $user->unreadNotifications()->count();

        $response = $this->actingAs($user)->getJson('/notifications/count');

        $response
            ->assertOk()
            ->assertExactJson(["count" => $unreadNotifications]);

        $this->assertEquals($unreadNotifications, $response->json()['count']);
    }

    /**
     * @test
     */
    public function single_notification_can_be_created()
    {
        User::factory()->count(2)->create();

        NotificationTestService::generateNotificationsToAllUsers(2);
        
        $this->assertEquals(4, User::first()->notifications()->count());
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
