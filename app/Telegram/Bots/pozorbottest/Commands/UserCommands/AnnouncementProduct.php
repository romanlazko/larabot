<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementProduct extends Command
{
    protected $name = 'product';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [
                array("Одежда", 'caption', 'clothes'),
                array("Аксессуары", 'caption', 'accessories'),
                array("Для дома", 'caption', 'for_home'),
            ],
            [
                array("Электроника", 'caption', 'electronics'),
                array("Спорт", 'caption', 'sport'),
                array("Мебель", 'caption', 'furniture'),
            ],
            [
                array("Книги", 'caption', 'books'),
                array("Игры", 'caption', 'games'),
                array("Авто-мото", 'caption', 'auto'),
            ],
            [
                array("Недвижимость", 'caption', 'property'),
                array("Животные", 'caption', 'animals'),
                array("Прочее", 'caption', 'other'),
            ],
            [array('🏠 Главное меню','menu','')],
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
