<?php

namespace App\Telegram\Entities\ChatMember;

use App\Telegram\Entities\BaseEntity;
use App\Telegram\Entities\User;

/**
 * Class ChatMemberLeft
 *
 * @link https://core.telegram.org/bots/api#chatmemberleft
 *
 * @method string getStatus() The member's status in the chat, always “left”
 * @method User   getUser()   Information about the user
 */
class ChatMemberLeft extends BaseEntity
{
    public static $map = [
        'status'                    => true,
        'user'                      => User::class,
    ];
}