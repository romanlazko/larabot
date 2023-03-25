<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class MenuCommand extends Command
{
    protected static $name = '/menu';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array('Создать объявление', 'new_announcement', '')],
            [array('Мои объявления', 'my_announcements', '')],
            [array('Правила', 'rulles', '')]
        ]);

        $data = [
            'text'          =>  "Привет 👋" ."\n\n". "Я помогу тебе создать объявление о продаже либо покупке в каналах *Pozor! Барахолка*",
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}
