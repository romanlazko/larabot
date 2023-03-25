<?php 
namespace App\Telegram\Commands\DefaultCommands;

use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class EmptyResponseCommand extends Command
{
    protected $name = 'empty_response';

    protected $enabled = true;

    public function execute(Update $updates)
    {
        return Response::fromRequest(['ok' => true]);
    }




}