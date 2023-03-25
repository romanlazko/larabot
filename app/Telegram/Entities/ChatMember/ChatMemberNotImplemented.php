<?php

namespace App\Telegram\Entities\ChatMember;

use App\Telegram\Entities\BaseEntity;
use App\Telegram\Entities\User;

/**
 * Class ChatMemberNotImplemented
 *
 * @method string getStatus() The member's status in the chat
 * @method User   getUser()   Information about the user
 */
class ChatMemberNotImplemented extends BaseEntity
{
    public static $map = [
        'status'                    => true,
        'user'                      => User::class,
    ];
}