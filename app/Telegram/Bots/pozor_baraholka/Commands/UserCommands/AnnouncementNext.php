<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementNext extends Command
{
    protected $name = 'next';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $next = $this->getConversation()->notes['next'];
        
        return $this->bot->executeCommand($next);

    }
}
