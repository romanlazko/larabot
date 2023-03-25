<?php 

namespace App\Telegram\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Update;
use App\Telegram\Telegram;

class StartCommand extends Command
{
    protected $name = 'start';

    protected $enabled = true;

    public function execute(Update $updates)
    {
        $data = [
            'text'      => "It is work",
            'chat_id'   => $updates->getChat()->getId(),
        ];

        return BotApi::sendMessage($data);
    }
}
