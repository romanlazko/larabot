<?php

use App\Telegram\Exceptions\TelegramException;
use App\Models\Bot;
use App\Telegram\Config;
use App\Telegram\DB;
use App\Telegram\Models\TelegramChatMemberUpdate;
use App\Telegram\Telegram;
use App\Telegram\TelegramLogDb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/telegram/{bot:bot_id}', function (Bot $bot) {
    try {
        $telegram = new Telegram($bot->token);
        $telegram->run();
    } catch (TelegramException | Throwable $exception) {
        TelegramLogDb::insertLog($exception);
    }
});

Route::get('/telegram/{bot:bot_id}', function (Bot $bot) {

    // $bot = Bot::where('bot_id', $request->id)->first();
    // $telegram = new Telegram($bot->token);

    // try{
    //     $buttons = $telegram::inlineKeyboard([
    //         [array('Клиенты все', 'bank_clients', '1')],
    //         [array('Записи', 'applying', '2')],
    //         [array('Расписание', 'city', '3')],
    //         [array('Назад', '/exit', '4')]
    //     ], 'user_id');
    //     // $response = $telegram::getWebhookInfo();
    //     $telegram::sendMessage(json_encode($telegram::getWebhookInfo(), JSON_UNESCAPED_UNICODE), $buttons, 54488352, '');
    // }catch(TelegramException $e){
    //     $telegram::sendMessage($e->getDescription(), [], 544883527, '');
    //     $telegram::sendMessage($e->getStringParams(), [], 544883527, '');
    // }
    // $telegram = new Telegram($bot->token);
    // return $telegram->executeCommand('defaul')->getJson();
    $telegram = new Telegram($bot->token);
    // $messages = TelegramMessage::get();
    
    // foreach ($messages as $key => $message) {
    //     $text = $message->callback_query()->first()->data ?? $message->text ?? $message->caption ?? $message->photo;
    //     dump($message);
    // }

    // dd(TelegramMessage::all());
    $chat_members = TelegramChatMemberUpdate::all();
    foreach ($chat_members as $chat_member) {
        dump($chat_members, $chat_member->getOldChatMember());
    }
    dd($telegram::getWebhookInfo(), $telegram::getMe(), $telegram->getAllCommandsList());
    // $telegram::sendMessage($telegram->getUpdates()->getJson(), [], 544883527);
});

Route::post('/vk/{id}', function (Request $request) {
    // return BotApi::sendMessage(544883527, json_encode(json_decode($request->getContent(), false)->message), '5981959980:AAHtBsJcUuXBfuR6FVgFfNh31r2jQwlF8io');
    
});