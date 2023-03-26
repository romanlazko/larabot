<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementCost extends Command
{
    public static $command = 'cost';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitCost::$expectation);
        
        $buttons = BotApi::inlineKeyboard([
            [array(MenuCommand::$title, MenuCommand::$command, '')]
        ]);

        $data = [
            'text'          => "Укажи в кронах *стоимость* товара."."\n\n"."_Максимально_ *12* _символов_.",
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons
        ];

        return BotApi::sendMessage($data);
    }




}
