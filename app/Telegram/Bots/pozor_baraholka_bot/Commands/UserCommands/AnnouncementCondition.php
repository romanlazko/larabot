<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementCondition extends Command
{
    public static $command = 'condition';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [
                array('Б/у', AnnouncementCategory::$command, 'used'),
                array('Новое', AnnouncementCategory::$command, 'new')
            ],
            [array(MenuCommand::$title, MenuCommand::$command, '')]
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
