<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AwaitCaption extends Command
{
    public static $expectation = 'await_caption';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $text           = $updates->getMessage()?->getText();

        if ($text === null) {
            $this->handleError("*Пришлите пожалуйста описание в виде текста.*");
            return $this->bot->executeCommand(AnnouncementCaption::$command);
        }

        if (iconv_strlen($text) > 800){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(AnnouncementCaption::$command);
        }

        $conversation->update([
            'caption' => $text,
        ]);
        
        return $this->bot->executeCommand(AnnouncementShow::$command);
    }




}
