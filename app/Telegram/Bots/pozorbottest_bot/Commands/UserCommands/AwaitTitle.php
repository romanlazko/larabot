<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AwaitTitle extends Command
{
    protected $name = 'title';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $text           = $updates->getMessage()?->getText();

        if ($text === null) {
            $this->handleError("*Пришлите пожалуйста заголовок в виде текста.*");
            return $this->bot->executeCommand('title');
        }

        if (iconv_strlen($text) > 31){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand('title');
        }

        $conversation->notes['title'] = $text;

        $conversation->update([
            'title' => $text
        ]);
        
        return $this->bot->executeCommand('cost');
    }
}
