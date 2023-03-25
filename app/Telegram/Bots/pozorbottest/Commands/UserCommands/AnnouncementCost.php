<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementCost extends Command
{
    protected $name = 'cost';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation('await_cost');
        
        $buttons = BotApi::inlineKeyboard([
            [array('🏠 Главное меню', '/menu', '')]
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
