<?php
namespace App\Telegram\Entities;

use App\Telegram\Entities\BaseEntity;

class CallbackQuery extends BaseEntity
{

    public static $map = [
        'id'                => true,
        'from'              => User::class,
        'message'           => Message::class,
        'inline_message_id'	=> true,
        'chat_instance'     => true,
        'data'              => InlineData::class,
        'game_short_name'	=> true,
    ];

    public function getCommand()
    {
        return $this->getData()->getButton();
    }
}