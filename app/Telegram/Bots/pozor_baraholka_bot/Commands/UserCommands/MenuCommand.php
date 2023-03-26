<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class MenuCommand extends Command
{
    public static $command = '/menu';

    public static $title = '🏠 Главное меню';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(NewAnnouncement::$title, NewAnnouncement::$command, '')],
            [array(MyAnnouncements::$title, MyAnnouncements::$command, '')],
            [array(RullesCommand::$title, RullesCommand::$command, '')]
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
