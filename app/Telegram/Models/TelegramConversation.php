<?php

namespace App\Telegram\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramConversation extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];
}
