<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class RullesCommand extends Command
{
    public static $command = 'rulles';

    public static $title = 'Правила';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(MenuCommand::$title, MenuCommand::$command, '')]
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
