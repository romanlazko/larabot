<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
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
        // $chat = BotApi::getChat(['chat_id' => $updates->getChat()->getId()])->getResult();
        // if (!$chat->getPinnedMessage()) {
        //     $text = [];

        //     $text[] = "Нажми *Начать*, что бы получить контакт на автора объявления.";

        //     $response = BotApi::sendMessage([
        //         'text' => implode("\n\n", $text),
        //         'chat_id' => $updates->getChat()->getId(),
        //         'parse_mode' => 'Markdown',
        //     ]);
        //     BotApi::pinChatMessage([
        //         'chat_id'       => $updates->getChat()->getId(),
        //         'message_id'    => $response->getResult()->getMessageId(),
        //     ]);
        // }
        return $this->bot->executeCommand(MenuCommand::$command);
    }
}
