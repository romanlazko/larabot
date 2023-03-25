<?php

namespace App\Http\Controllers\Admin;

use App\Telegram\Exceptions\TelegramException;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Telegram\Telegram;
use Illuminate\Http\Request;

class BotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $bots = auth()->user()->bots;
        return view('admin.bots.index.index', compact([
            'bots'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'url'   => 'required|url',
            'token' => 'required|regex:/[0-9]{1,}:\w*/',
        ]);

        try {
            $token = explode(':', $request->token);

            $response = (new Telegram($request->token))::setWebHook([
                'url' => "{$request->url}/{$token[0]}",
            ]);
        }
        catch (TelegramException $response){
            return redirect()->back()->with([
                'ok' => $response->getOk(), 
                'description' => $response->getMessage()
            ]);
        }

        if ($response->getOk()) {
            auth()->user()->bots()->updateOrCreate([
                'bot_id' => $token[0],
                'token' => $request->token,
            ]);
        }
        
        return redirect()->back()->with([
            'ok' => $response->getOk(), 
            'description' => $response->getDescription()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bot  $bot
     * @return \Illuminate\Http\Response
     */
    public function show(Bot $bot)
    {
        $chats = $bot->chats()->select('telegram_chats.*')
            ->leftJoin('telegram_messages', function($join) {
                $join->on('telegram_chats.id', '=', 'telegram_messages.chat')
                    ->whereRaw('telegram_messages.id = (select max(id) from telegram_messages where chat = telegram_chats.id)');
            })
            ->orderByRaw('coalesce(telegram_messages.created_at, telegram_chats.created_at) desc')
            ->simplePaginate(20);

        $updates = $bot->updates()->orderBy('created_at')->limit(50)->get();

        return view('admin.bots.show.index', compact([
            'chats',
            'updates',
            'bot'
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bot  $bot
     * @return \Illuminate\Http\Response
     */
    public function edit(Bot $bot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bot  $bot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bot $bot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bot  $bot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bot $bot)
    {
        //
    }
}
