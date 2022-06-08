<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Services\Tests\NotificationTestService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationTest extends TestCase
{   
    use DatabaseTransactions;

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
            ->assertJsonFragment([
                'title' => 'Sample One',
                'title' => 'Sample Two',
            ])
            ->assertJsonMissing([
                'title' => 'Sample Three',
            ])
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
            ->assertJsonFragment([
                'title' => 'Sample One',
                'title' => 'Sample Two',
            ])
            ->assertJsonMissing([
                'title' => 'Sample Three',
            ])
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
    public function fetch_filtered_by_date_range_notifications()
    {
        User::factory()->count(1)->create();

        $user = User::first();

        $subDays = [360, 240, 190, 130, 120, 30, 25, 20, 15, 7, 2];

        foreach ($subDays as $subDay) {
            NotificationTestService::generateNotificationsToAllUsers(1);

            $user->notifications()->take(2)->update([
                'created_at' => Carbon::parse('2022-06-08')->subDays($subDay),
                'updated_at' => Carbon::parse('2022-06-08')->subDays($subDay),
            ]);
        }

        $response = $this->actingAs($user)->getJson('/notifications?date_start=2022-02-08&date_end=2022-05-19');

        $response
            ->assertOk()
            ->assertJsonCount(8, 'data')
            ->assertJsonFragment([
                'self' => '2022-05-19 00:00:00',
                'self' => '2022-05-14 00:00:00',
                'self' => '2022-05-09 00:00:00',
                'self' => '2022-02-08 00:00:00',
            ])
            ->assertJsonMissing([
                'self' => '2022-06-06 00:00:00',
                'self' => '2022-06-01 00:00:00',
            ])
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


        // collect($response->json()['data'])->filter(function($value) {
        //     dump($value['created_at']['self']);
        // });

        // dd($user->notifications()->select('created_at')->get()->filter(function($value) {
        //     dump($value->created_at->format('Y-m-d H:i:s'));
        // }));
    }    

    /**
     * @test
     */
    public function notification_can_be_created()
    {
        User::factory()->count(2)->create();

        NotificationTestService::generateNotificationsToAllUsers(5);
        
        $this->assertEquals(10, User::first()->notifications()->count());
    }    

    /**
     * @test
     */
    public function notification_can_be_mark_as_read()
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

    /**
     * @test
     */
    public function notification_cant_be_mark_as_read_without_readat_field()
    {
        $users = User::factory()->count(2)->create();

        NotificationTestService::generateNotificationsToAllUsers(3);
        
        $user = User::first();

        $notificationId = $user->notifications()->inRandomOrder()->first()->id;

        $response = $this->actingAs($user)->patchJson('/notifications/'.$notificationId, [
            'read_at' => '',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['read_at']);
    }

    /**
     * @test
     */
    public function notification_cant_be_mark_as_read_with_invalid_date()
    {
        $users = User::factory()->count(2)->create();

        NotificationTestService::generateNotificationsToAllUsers(3);
        
        $user = User::first();

        $notificationId = $user->notifications()->inRandomOrder()->first()->id;

        $response = $this->actingAs($user)->patchJson('/notifications/'.$notificationId, [
            'read_at' => 'sample',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['read_at']);
    }
}
