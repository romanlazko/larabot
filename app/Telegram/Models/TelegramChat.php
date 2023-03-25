<?php

namespace App\Telegram\Models;

use App\Models\Bot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramChat extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function messages()
    {
        return $this->hasMany(TelegramMessage::class, 'chat', 'id');
    }

    public function bot()
    {
        return $this->belongsTo(Bot::class, 'bot_id', 'id');
    }

    public function photo()
    {
        return $this->bot()->first()->getTelegram()::getPhoto(['file_id' => $this->photo]);
    }
}
