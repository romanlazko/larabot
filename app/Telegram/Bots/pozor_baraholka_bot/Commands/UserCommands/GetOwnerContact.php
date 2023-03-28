<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Bots\pozor_baraholka_bot\Models\Announcement;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;
use App\Telegram\Exceptions\TelegramUserException;

class GetOwnerContact extends Command
{
    public static $command = 'get_owner_contact';

    public static $title = '';

    protected $pattern = "/^(\/start\s)(announcement)=(\d+)$/";

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        preg_match($this->pattern, $updates->getMessage()?->getCommand(), $matches);

        $announcement = Announcement::findOr($matches[3] ?? null, function () {
            throw new TelegramUserException("Объявление не найдено");
        });

        if ($announcement->status === 'published') {
            $announcement->update([
                'views' => $announcement->views+1
            ]);
        }else if ($announcement->status === 'irrelevant') {
            return BotApi::sendMessage([
                'text'          => 'Объявление уже не актуально.',
                'chat_id'       => $updates->getChat()->getId(),
            ]);
        }

        $buttons = BotApi::inlineKeyboardWithLink([
            'text'  => "👤 Контакт на автора", 
            'url'   => "tg://user?id={$announcement->user_id}"
        ]);
        return BotApi::sendMessage([
            'text'          => 'Вот контакт на автора объявления',
            'reply_markup'  => $buttons,
            'chat_id'       => $updates->getChat()->getId(),
        ]);
    }
}
