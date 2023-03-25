<?php

namespace App\Telegram\Models;

use App\Telegram\Entities\ChatInviteLink;
use App\Telegram\Entities\ChatMember\ChatMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramChatMemberUpdate extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function getOldChatMember()
    {
        if($this->old_chat_member) {
            return ChatMember::fromRequest(json_decode($this->old_chat_member, true));
        }
        return null;
    }

    public function getNewChatMember()
    {
        if($this->old_chat_member) {
            return ChatMember::fromRequest(json_decode($this->new_chat_member, true));
        }
        return null;
    }

    public function getInviteLink()
    {
        if($this->old_chat_member) {
            return ChatInviteLink::fromRequest(json_decode($this->invite_link, true));
        }
        return null;
    }
}
