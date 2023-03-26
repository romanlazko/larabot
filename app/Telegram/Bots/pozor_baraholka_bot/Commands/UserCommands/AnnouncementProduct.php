<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementProduct extends Command
{
    public static $command = 'product';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [
                array("Одежда", AnnouncementCaption::$command, 'clothes'),
                array("Аксессуары", AnnouncementCaption::$command, 'accessories'),
                array("Для дома", AnnouncementCaption::$command, 'for_home'),
            ],
            [
                array("Электроника", AnnouncementCaption::$command, 'electronics'),
                array("Спорт", AnnouncementCaption::$command, 'sport'),
                array("Мебель", AnnouncementCaption::$command, 'furniture'),
            ],
            [
                array("Книги", AnnouncementCaption::$command, 'books'),
                array("Игры", AnnouncementCaption::$command, 'games'),
                array("Авто-мото", AnnouncementCaption::$command, 'auto'),
            ],
            [
                array("Недвижимость", AnnouncementCaption::$command, 'property'),
                array("Животные", AnnouncementCaption::$command, 'animals'),
                array("Прочее", AnnouncementCaption::$command, 'other'),
            ],
            [array(MenuCommand::$title, MenuCommand::$command, '')],
        ], 'category');

        $data = [
            'text'          => "Выбери к какой *категории* относятся товары.",
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()->getMessage()->getMessageId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons
        ];

        return BotApi::editMessageText($data);
    }




}
