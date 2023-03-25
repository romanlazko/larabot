<?php

namespace App\Telegram\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramUser extends Model
{
    use HasFactory; use SoftDeletes;

    protected $fillable = [
        'bot_id',
        'user_id',
        'is_bot',
        'first_name',
        'last_name',
        'username',
        'language_code',
        'is_premium',
        'added_to_attachment_menu',
        'can_join_groups',
        'can_read_all_group_messages',
        'supports_inline_queries',
        'last_response_message_id',
        'expectation'
    ];

    public function conversation()
    {
        return $this->hasMany(TelegramConversation::class, 'user', 'id');
    }
}
