<?php 

namespace App\Telegram\Commands\DefaultCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class DefaultCommand extends Command
{
    protected $name = 'default';

    protected $enabled = true;

    public function execute(Update $updates)
    {
        // return Response::fromRequest(['ok' => true]);
        $data = [
            'text'      => "It is default command",
            'chat_id'   => $updates->getChat()->getId(),
        ];

        return BotApi::sendMessage($data);
    }




}
