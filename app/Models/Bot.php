<?php

namespace App\Models;

use App\Telegram\Models\TelegramUser;
use App\Telegram\Models\TelegramChat;
use App\Telegram\Models\TelegramLog;
use App\Telegram\Models\TelegramUpdate;
use App\Telegram\Models\TgConversation;
use App\Telegram\Telegram;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bot extends Model
{
    use HasFactory; use SoftDeletes;

    protected $telegram         = null;
    protected $chat             = null;
    protected $webhookInfo      = null;

    protected $fillable = [
        'owner_id',
        'bot_id',
        'token',
    ];

    public function owner()
    {
        return $this->belongsTo(Bot::class, 'owner_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(TelegramUser::class, 'bot_id', 'id');
    }

    public function chats()
    {
        return $this->hasMany(TelegramChat::class, 'bot_id', 'id');
    }

    public function updates()
    {
        return $this->hasMany(TelegramUpdate::class, 'bot_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(TelegramLog::class, 'bot_id', 'id');
    }

    public function getTelegram(){
        if ($this->telegram === null) {
            $this->telegram = new Telegram($this->token);
        }
        return $this->telegram;
    }


    public function me()
    {
        if ($this->chat === null) {
            $this->chat = $this->getTelegram()::getChat(['chat_id' => $this->bot_id])->getResult();
        }
        return $this->chat;
    }

    public function webhookInfo()
    {
        if ($this->webhookInfo === null) {
            $this->webhookInfo = $this->getTelegram()::getWebhookInfo()->getResult();
        }
        return $this->webhookInfo;
    }
}
