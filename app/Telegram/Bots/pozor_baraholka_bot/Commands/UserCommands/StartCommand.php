<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class StartCommand extends Command
{
    public static $command = 'start';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        return $this->bot->executeCommand(MenuCommand::$command);
    }
}
