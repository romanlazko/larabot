<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AwaitCost extends Command
{
    protected $name = 'await_cost';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $text           = $updates->getMessage()?->getText();

        if ($text === null) {
            $this->handleError("*Пришлите пожалуйста стоимость в виде текстового сообщения.*");
            return $this->bot->executeCommand('cost');
        }

        if (iconv_strlen($text) > 12){
            $this->handleError("*Слишком много символов*");
            return $this->bot->executeCommand('cost');
        }

        if (!is_numeric($text)){
            $this->handleError("*Стоимость должна быть указана только цифрами*");
            return $this->bot->executeCommand('cost');
        }

        $conversation->update([
            'cost' => $text,
        ]);

        return $this->bot->executeCommand('condition');
    }
}
