<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementCondition extends Command
{
    protected $name = 'condition';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [
                array('Б/у', 'category', 'used'),
                array('Новое', 'category', 'new')
            ],
            [array('🏠 Главное меню', '/menu', '')]
        ], 'condition');

        $data = [
            'text'          => "В каком *состоянии* находится товар?",
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons
        ];

        return BotApi::sendMessage($data);
    }




}
