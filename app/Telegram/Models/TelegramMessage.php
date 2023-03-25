<?php

namespace App\Telegram\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramMessage extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function callback_query()
    {
        return $this->belongsTo(TelegramCallbackQuery::class, 'id', 'message');
    }

    public function from()
    {
        return $this->belongsTo(TelegramUser::class, 'from', 'id');
    }

    public function chat()
    {
        return $this->belongsTo(TelegramChat::class, 'chat', 'id');
    }

    public function photo()
    {
        return $this->chat()->first()->bot()->first()->getTelegram()::getPhoto(['file_id' => $this->photo]);
    }
}
