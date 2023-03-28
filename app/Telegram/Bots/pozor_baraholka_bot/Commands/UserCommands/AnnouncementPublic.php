<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Bots\pozor_baraholka_bot\Models\Announcement;
use App\Telegram\Commands\Command;
use App\Telegram\DB;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;
use App\Telegram\Exceptions\TelegramException;
use App\Telegram\Bots\pozor_baraholka_bot\Commands\AdminCommands\MenuCommand as AdminMenuCommand;
use App\Telegram\Exceptions\TelegramUserException;

class AnnouncementPublic extends Command
{
    public static $command = 'public';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $chat                   = $updates->getChat();
        $chatInfo               = BotApi::getChat(['chat_id' => $chat->getId()]);
        $hasPrivateForwards     = $chatInfo->getResult()->getHasPrivateForwards();

        if($hasPrivateForwards AND $hasPrivateForwards === true){
            return $this->sendBadResponseIfCantForward($updates);
        }

        if (!$this->createAnnouncement()) {
            throw new TelegramUserException("Ошибка сохранения объявления.");
        }

        return $this->sendResponse($updates);
    }

    private function sendResponse(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(MenuCommand::$title, MenuCommand::$command, '')],
        ]);

        $data = [
            'text'          =>  "*Спасибо*"."\n\n".
                                "Если объявление соответствует [правилам](https://telegra.ph/Pravila-pablika-PozorBaraholka-03-21), то мы в ближайшее время его опубликуем.",
            'reply_markup'  => $buttons,
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()->getMessage()->getMessageId(),
            'parse_mode'    => "Markdown",
        ];
        
        return BotApi::editMessageText($data);
    }

    private function sendAdminsNotify()
    {
        $buttons = BotApi::inlineKeyboard([
            [array(AdminMenuCommand::$title, AdminMenuCommand::$command, '')],
        ]);
        BotApi::sendMessages([
            'text'          => "Новое объявление",
            'chat_ids'      => DB::getAdmins(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons,
        ]);
    }

    private function sendBadResponseIfCantForward(Update $updates)
    {
        try {
            $buttons = BotApi::inlineKeyboard([
                [array('Продолжить', AnnouncementPublic::$command, '')],
            ]);

            return BotApi::editMessageText([
                'text'          =>  "*Ой ой*"."\n\n".
                                    "Мы не можем опубликовать объявление поскольку твои настройки конфиденциальности не позволяют нам сослаться на тебя."."\n\n".
                                    "Сделай все как указанно в [инструкции](https://telegra.ph/Kak-razreshit-peresylku-soobshchenij-12-27) что бы исправить это."."\n\n".
                                    "*Настройки конфиденциальности вступят в силу в течении 5-ти минут, после этого нажми на кнопку «Продолжить»*",
                'reply_markup'  => $buttons,
                'chat_id'       => $updates->getChat()->getId(),
                'message_id'    => $updates->getCallbackQuery()->getMessage()->getMessageId(),
                'parse_mode'    => "Markdown",
            ]);
        }
        catch(TelegramException $e){
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text'              => "Настройки еще не вступили в силу. Подождите 5 минут после изменения настроек и попробуйте еще раз.",
                'show_alert'        => true
            ]);
        }
    }

    private function createAnnouncement()
    {
        $conversation   = $this->getConversation();
        $notes          = $conversation->notes;

        $announcement = Announcement::updateOrCreate([
            'user_id'       => $conversation->getUserId(),
            'city'          => $notes['city'] ?? null,
            'type'          => $notes['type'] ?? null,
            'title'         => $notes['title'] ?? null,
            'caption'       => $notes['caption'] ?? null,
            'cost'          => $notes['cost'] ?? null,
            'condition'     => $notes['condition'] ?? null,
            'category'      => $notes['category'] ?? null,
            'status'        => 'new',
        ]);

        if (array_key_exists('photo', $notes)) {
            foreach ($notes['photo'] as $id => $file_id) {
                $announcement->photo()->create([
                    'file_id' => $file_id,
                ]);
            }
        }
        
        return $announcement;
    }
}
