<?php

use App\Http\Controllers\Admin\ChatController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect(route('bots.index'));
});

Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'verified']], function(){
    Route::get('/', function () {
        return redirect(route('bots.index'));
    });
    Route::get('dashboard', function () {
        return redirect(route('bots.index'));
    });

    Route::group(['prefix' => 'profile'], function(){
        Route::get('/', 'ProfileController@edit')->name('profile.edit');
        Route::patch('/', 'ProfileController@update')->name('profile.update');
        Route::delete('/', 'ProfileController@destroy')->name('profile.destroy');
    });


    Route::resource('bots', 'BotController');
    
    // Route::group(['prefix' => 'bots/{bot}'], function(){
        Route::resource('chats', 'ChatController');

        Route::group(['prefix' => 'chats/{chat}'], function(){
            Route::resource('message', 'MessageController');
        });
    // });
   

    // Route::post('/message/{id}', 'MessageController@send')->name('message.send');

});


// require __DIR__.'/auth.php';
