<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;
use App\Telegram\Exceptions\TelegramException;
use App\Telegram\Exceptions\TelegramUserException;

class AnnouncementShow extends Command
{
    public static $command = 'show';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $notes  = $this->getConversation()->notes;

        try {
            (isset($notes['photo']))
                ? $this->sendMessageWithMedia($updates, $notes)
                : $this->sendMessageWithoutMedia($updates, $notes);
        }
        catch (TelegramException $exception) {
            throw new TelegramUserException("Ошибка публикации: {$exception->getMessage()}");
        }
        
        return $this->sendConfirmMessage($updates);
    }

    private function createAdText(array $notes): string
    {
        $text = [];
        
        $text['type'] = $notes['type'] === 'buy' ? "#куплю" : "#продам";

        // If notes have a title, add it to the ad text
        if (array_key_exists('title', $notes)) {
            $text['title'] = "<b>{$notes['title']}</b>";
        }

        // Add caption to the ad text
        $text['caption'] = $notes['caption'];

        // If notes have a product condition, add it to the ad text
        if (array_key_exists('condition', $notes)) {
            $text['condition'] = $notes['condition'] === 'new' ? "<i>Состояние:</i> Новое" : "<i>Состояние:</i> Б/у";
        }

        // If notes have a price, add it to the ad text
        if (array_key_exists('cost', $notes)) {
            $text['cost'] = "<i>Цена:</i> {$notes['cost']} CZK";
        }

        // Map the category abbreviation to the category name
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

        $text['category'] = $category_arr[$notes['category']];

        // Сформировать итоговый текст объявления
        return implode("\n\n", $text);
    }

    private function sendConfirmMessage(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array('Публикуем', AnnouncementPublic::$command, '')],
            [array(MenuCommand::$title, MenuCommand::$command, '')]
        ]);

        return BotApi::sendMessage([
            'text'          => "Так будет выглядеть твое объявление." ."\n\n". "*Публикуем?*", 
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => 'Markdown',
            'reply_markup'  => $buttons
        ]);
    }

    private function sendMessageWithMedia(Update $updates, array $notes): Response
    {
        $media  = BotApi::setInputMediaPhoto($notes['photo'], $this->createAdText($notes), 'HTML');

        return BotApi::sendMediaGroup([
            'chat_id'               => $updates->getChat()->getId(),
            'media'                 => $media,
            'disable_notification'  => true,
        ]);
    }

    private function sendMessageWithoutMedia(Update $updates, array $notes): Response
    {
        return BotApi::sendMessage([
            'chat_id'       => $updates->getChat()->getId(),
            'text'          => $this->createAdText($notes), 
            'parse_mode'    => 'HTML',
        ]);
    }
}
