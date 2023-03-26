<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Config;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class NewAnnouncement extends Command
{
    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $this->getConversation()->clear();

        $buttons = BotApi::inlineKeyboard([
            [
                array('Прага', 'type', Config::get('prague_channel')),
                array('Брно', 'type', Config::get('brno_channel'))
            ],
            [array('🏠 Главное меню', '/menu', '')]
        ], 'city');

        $data = [
            'text'          => "В каком *городе* ты хочешь опубликовать объявление?",
            'reply_markup'  => $buttons,
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()->getMessage()->getMessageId(),
            'parse_mode'    => "Markdown"
        ];
                                
        return BotApi::editMessageText($data);
    }
}
