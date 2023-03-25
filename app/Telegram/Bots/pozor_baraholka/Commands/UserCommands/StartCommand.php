<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class StartCommand extends Command
{
    protected $name = 'start';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        return $this->bot->executeCommand('/menu');
    }
}
