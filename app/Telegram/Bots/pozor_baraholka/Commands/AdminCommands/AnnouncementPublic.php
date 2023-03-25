<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\AdminCommands;

use App\Telegram\BotApi;
use App\Telegram\Bots\pozorbottest\Models\Announcement;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;
use App\Telegram\Exceptions\TelegramException;
use App\Telegram\Exceptions\TelegramUserException;

class AnnouncementPublic extends Command
{
    protected $name = 'public';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcement = Announcement::findOr($updates->getInlineData()?->getAnnouncementId(), function () {
            throw new TelegramUserException("Объявление не найдено");
        });

        try {
            if (count($announcement->photo) > 0) {
                $response = $this->sendMessageWithMedia($announcement);
            } else {
                $response = $this->sendMessageWithoutMedia($announcement);
            }
            if ($response->getOk()) {
                $announcement->update([
                    'status' => 'public'
                ]);
                BotApi::sendMessage([
                    'chat_id'       => $announcement->user_id,
                    'text'          => "Ваше объявление опубликовано", 
                    'parse_mode'    => 'HTML',
                ]);

            }
            return $this->bot->executeCommand('/menu');
        }catch (TelegramException $exception) {
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

        $text['contact'] = "<a href='https://t.me/pozorbottestbot?start=announcement={$announcement->id}'>🔗Контакт</a>";



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

    private function sendMessageWithMedia(Announcement $announcement): Response
    {
        $media = BotApi::setInputMediaPhoto($announcement->photo->pluck('file_id'), $this->createAdText($announcement), 'HTML');
        
        return BotApi::sendMediaGroup([
            'chat_id'               => $announcement->city,
            'media'                 => $media,
        ]);
    }

    private function sendMessageWithoutMedia(Announcement $announcement): Response
    {
        return BotApi::sendMessage([
            'chat_id'       => $announcement->city,
            'text'          => $this->createAdText($announcement), 
            'parse_mode'    => 'HTML',
        ]);
    }
}