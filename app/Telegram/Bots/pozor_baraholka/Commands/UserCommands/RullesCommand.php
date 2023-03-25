<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class RullesCommand extends Command
{
    protected $name = 'rulles';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array('🏠 Главное меню', MenuCommand::$name, '')]
        ]);

        $data = [
            'text'          =>  "C правилами можно ознакомиться [тут](https://telegra.ph/Pravila-pablika-PozorBaraholka-03-21).",
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ];

        return BotApi::editMessageText($data);
    }
}
