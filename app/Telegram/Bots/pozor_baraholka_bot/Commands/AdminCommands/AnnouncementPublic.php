<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\AdminCommands;

use App\Telegram\BotApi;
use App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands\ShowMyAnnouncement as UserShowAnnouncement;
use App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands\MenuCommand as UserMenuCommand;
use App\Telegram\Config;
use App\Telegram\Bots\pozor_baraholka_bot\Models\Announcement;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;
use App\Telegram\Exceptions\TelegramException;
use App\Telegram\Exceptions\TelegramUserException;

class AnnouncementPublic extends Command
{
    public static $command = 'public';

    public static $title = 'Публикуем';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcement = Announcement::findOr($updates->getInlineData()?->getAnnouncementId(), function () {
            throw new TelegramUserException("Объявление не найдено");
        });

        if ($announcement->status === 'published') {
            throw new TelegramUserException("Объявление уже опубликовано");
        }

        if ($announcement->status === 'irrelevant') {
            throw new TelegramUserException("Объявление уже не актуально");
        }

        try {
            if (count($announcement->photo) > 0) {
                $response = $this->sendMessageWithMedia($announcement);
            } else {
                $response = $this->sendMessageWithoutMedia($announcement);
            }
            if ($response->getOk()) {
                $announcement->update([
                    'status' => 'published'
                ]);

                $buttons = BotApi::inlineKeyboard([
                    [array($announcement->title ?? $announcement->caption, UserShowAnnouncement::$command, $announcement->id)],
                    [array(UserMenuCommand::$title, UserMenuCommand::$command, '')]
                ], 'announcement_id');

                BotApi::sendMessage([
                    'chat_id'       => $announcement->user_id,
                    'text'          => "Ваше объявление опубликовано",
                    'reply_markup'  => $buttons,
                    'parse_mode'    => 'HTML',
                ]);
                return $this->bot->executeCommand(MenuCommand::$command);
            }
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

        $text['contact'] = "<a href='https://t.me/pozor_baraholka_bot?start=announcement={$announcement->id}'>🔗Контакт</a>" . " (<i>Перейди по ссылке и нажми <b>Начать</b></i>)";

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
            'chat_id' => Config::get($announcement->city),
            'media'   => $media,
        ]);
    }

    private function sendMessageWithoutMedia(Announcement $announcement): Response
    {
        return BotApi::sendMessage([
            'chat_id'       => Config::get($announcement->city),
            'text'          => $this->createAdText($announcement), 
            'parse_mode'    => 'HTML',
        ]);
    }
}
