<?php 

namespace App\Telegram\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Update;

class HelpCommand extends Command
{
    protected $name = 'help';

    protected $enabled = true;

    public function execute(Update $updates)
    {
        $data = [
            'text'      => "It is a default help command",
            'chat_id'   => $updates->getChat()->getId(),
        ];

        return BotApi::sendMessage($data);
    }




}
