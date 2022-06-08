<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::get('/bypass', function() {
    DB::transaction(function() {
        DB::table('users')->truncate();
        DB::table('notifications')->truncate();
    });
    User::factory()->create();
    Auth::login(User::first());
    return redirect('/notifications');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount']);
    Route::patch('/notifications/{id}', [NotificationController::class, 'markAsReadById']);
});