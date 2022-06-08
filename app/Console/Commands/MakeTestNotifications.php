<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\Tests\NotificationTestService;

class MakeTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:test-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make tests notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating notifications to all users...');

        DB::transaction(function() {
            DB::table('users')->truncate();
            DB::table('notifications')->truncate();
        });

        User::factory()->count(2)->create();

        NotificationTestService::generateNotificationsToAllUsers(10);
    }
}
