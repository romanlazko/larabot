<?php 

namespace App\Telegram\Commands\SupergroupCommands;

use App\Telegram\Commands\Command;
use App\Telegram\Entities\Update;
use App\Telegram\Telegram;

class StartCommand extends Command
{
    protected $name = 'start';

    protected $enabled = true;

    public function execute(Update $updates)
    {
        // return $bot::sendMessage("It is work");
    }
}
