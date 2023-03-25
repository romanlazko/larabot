<?php

namespace App\Telegram\Entities\ChatMember;

use App\Telegram\Entities\BaseEntity;
use App\Telegram\Entities\User;

/**
 * Class ChatMemberMember
 *
 * @link https://core.telegram.org/bots/api#chatmembermember
 *
 * @method string getStatus() The member's status in the chat, always “member”
 * @method User   getUser()   Information about the user
 */
class ChatMemberMember extends BaseEntity
{
    public static $map = [
        'status'                    => true,
        'user'                      => User::class,
    ];
}