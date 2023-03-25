<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\SupergroupCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class DaCommand extends Command
{
    protected $name = 'Да';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        return BotApi::sendMessage([
            'text'      => "Пидора еда",
            'chat_id'   => $updates->getChat()->getId()
        ]);
    }
}
