<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementType extends Command
{
    public static $command = 'type';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $city           = $updates->getInlineData()->getCity();

        $conversation->update([
            'city' => $city
        ]);

        $buttons = BotApi::inlineKeyboard([
            [
                array('Продать', AnnouncementCount::$command, 'sell'),
                array('Купить', AnnouncementPhoto::$command, 'buy')
            ],
            [array(MenuCommand::$title, MenuCommand::$command, '')]
        ], 'type');

        $data = [
            'text'          => "Какой *тип* объявления ты хочешь прислать?",
            'reply_markup'  => $buttons,
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()->getMessage()->getMessageId(),
            'parse_mode'    => "Markdown"
        ];
                
        return BotApi::editMessageText($data);
        
    }
}
