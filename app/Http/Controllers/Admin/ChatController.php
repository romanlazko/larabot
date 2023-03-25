<?php

namespace App\Http\Controllers\Admin;

use App\Telegram\Exceptions\TelegramException;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Telegram\Models\TelegramChat;
use App\Telegram\Telegram;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\Telegram\Models\TelegramChat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show(TelegramChat $chat)
    {
        $messages   = $chat->messages()->orderBy('id', 'DESC')->paginate(10);

        return view('admin.bots.chat.show.index', compact([
            'chat',
            'messages'
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Telegram\Models\TelegramChat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(TelegramChat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Telegram\Models\TelegramChat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TelegramChat $chat)
    {
        $chat->update([
            'role' => $request->role
        ]);
        return back();
    }
}
