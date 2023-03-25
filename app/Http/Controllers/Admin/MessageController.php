<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Telegram\Exceptions\TelegramException;
use App\Telegram\Models\TelegramChat;
use App\Telegram\Models\TelegramMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TelegramChat $chat)
    {
        try {
            $response = $chat->bot()->first()->getTelegram()::sendMessage([
                'text'      => $request->message,
                'chat_id'   => $chat->chat_id,
            ]);
            return redirect()->back()->with(['ok' => $response->getOk(), 'description' => $response->getDescription()]);
        }
        catch (TelegramException $exception){
            return redirect()->back()->with(['ok' => $exception->getOk(), 'description' => $exception->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Telegram\Models\TelegramMessage  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(TelegramMessage $message)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Telegram\Models\TelegramMessage  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TelegramMessage $message)
    {
        try {
            $chat = $message->chat()->first();
            $response = $chat->bot()->first()->getTelegram()::editMessageText([
                'text'          => $request->message,
                'chat_id'       => $chat->chat_id,
                'message_id'    => $message->message_id,
            ]);
            return redirect()->back()->with(['ok' => $response->getOk(), 'description' => $response->getDescription()]);
        }
        catch (TelegramException $exception){
            return redirect()->back()->with(['ok' => $exception->getOk(), 'description' => $exception->getMessage()]);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Telegram\Models\TelegramMessage  $telegramMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(TelegramMessage $telegramMessage)
    {
        //
    }
}
