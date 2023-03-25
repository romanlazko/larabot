<?php 

namespace App\Telegram\Commands\DefaultCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Update;

class SendResultCommand extends Command
{
    protected $name = 'send_result';

    protected $enabled = true;

    public function execute(Update $updates)
    {
        // return $bot::sendMessage($updates->getJson());
        $data = [
            'text'                  => $updates->getPrettyString(),
            'chat_id'               => $updates->getChat()->getId(),
            'message_id'            => $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            'message_thread_id'     => $updates->getCallbackQuery()?->getMessage()?->getMessageThreadId() ?? $updates->getMessage()?->getMessageThreadId(),
        ];
        
        return BotApi::sendMessage($data);
    }
}
