<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Bots\pozorbottest\Models\Announcement;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;
use App\Telegram\Exceptions\TelegramException;
use App\Telegram\Exceptions\TelegramUserException;

class ShowMyAnnouncement extends Command
{
    public static $command = 'show_announcement';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcement = Announcement::findOr($updates->getInlineData()?->getAnnouncementId(), function () {
            throw new TelegramUserException("Объявление не найдено");
        });

        if ($announcement->status === 'irrelevant') {
            throw new TelegramUserException("Объявление уже не актуально");
        }

        try {
            if (count($announcement->photo) > 0) {
                $this->sendMessageWithMedia($updates, $announcement);
            } else {
                $this->sendMessageWithoutMedia($updates, $announcement);
            }

            return $this->sendConfirmMessage($updates, $announcement);
        }
        catch (TelegramException $exception) {
            throw new TelegramUserException("Ошибка публикации: {$exception->getMessage()}");
        }
    }
    
    private function createAdText($announcement): string
    {
        $text = [];
        
        $text['type'] = $announcement->type === 'buy' ? "#куплю" : "#продам";

        // If announcement have a title, add it to the ad text
        if ($announcement->title) {
            $text['title'] = "<b>{$announcement->title}</b>";
        }

        // Add caption to the ad text
        $text['caption'] = $announcement->caption;

        // If notes have a product condition, add it to the ad text
        if ($announcement->condition) {
            $text['condition'] = $announcement->condition === 'new' ? "<i>Состояние:</i> Новое" : "<i>Состояние:</i> Б/у";
        }

        // If notes have a price, add it to the ad text
        if ($announcement->cost) {
            $text['cost'] = "<i>Цена:</i> {$announcement->cost} CZK";
        }

        $category_arr = [
            'clothes'       => '#одежда',
            'accessories'   => '#аксессуары',
            'for_home'      => '#для_дома',
            'electronics'   => '#электроника',
            'sport'         => '#спорт',
            'furniture'     => '#мебель',
            'books'         => '#книги',
            'games'         => '#игры',
            'auto'          => '#авто_мото',
            'property'      => '#недвижимость',
            'animals'       => '#животные',
            'other'         => '#прочее',
        ];

        $text['category'] = $category_arr[$announcement->category];

        // Сформировать итоговый текст объявления
        return implode("\n\n", $text);
    }

    private function sendConfirmMessage(Update $updates, Announcement $announcement): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(IrrelevantAnnouncement::$title, IrrelevantAnnouncement::$command, $announcement->id)],
            [array(MenuCommand::$title, MenuCommand::$command, '')]
        ], 'announcement_id');

        $text = [];
        $text['views']      = "Количество показов контакта на Вас: *{$announcement->views}*";
        $text['public_at']  = "Дата публикации: *{$announcement->updated_at->format('d.m.Y')}*";

        return BotApi::sendMessage([
            'text'          => implode("\n\n", $text), 
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => 'Markdown',
            'reply_markup'  => $buttons
        ]);
    }

    private function sendMessageWithMedia(Update $updates, Announcement $announcement): Response
    {
        $media  = BotApi::setInputMediaPhoto($announcement->photo->pluck('file_id'), $this->createAdText($announcement), 'HTML');
        
        return BotApi::sendMediaGroup([
            'chat_id'               => $updates->getChat()->getId(),
            'media'                 => $media,
            'disable_notification'  => true,
        ]);
    }

    private function sendMessageWithoutMedia(Update $updates, Announcement $announcement): Response
    {
        return BotApi::sendMessage([
            'chat_id'       => $updates->getChat()->getId(),
            'text'          => $this->createAdText($announcement), 
            'parse_mode'    => 'HTML',
        ]);
    }
}
