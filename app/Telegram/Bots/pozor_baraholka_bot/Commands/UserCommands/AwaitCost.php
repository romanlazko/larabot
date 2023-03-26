<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AwaitCost extends Command
{
    public static $expectation = 'await_cost';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $text           = $updates->getMessage()?->getText();

        if ($text === null) {
            $this->handleError("*Пришлите пожалуйста стоимость в виде текстового сообщения.*");
            return $this->bot->executeCommand(AnnouncementCost::$command);
        }

        if (iconv_strlen($text) > 12){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand(AnnouncementCost::$command);
        }

        if (!is_numeric($text)){
            $this->handleError("*Стоимость должна быть указана только цыфрами*");
            return $this->bot->executeCommand(AnnouncementCost::$command);
        }

        $conversation->update([
            'cost' => $text,
        ]);

        return $this->bot->executeCommand(AnnouncementCondition::$command);
    }
}
