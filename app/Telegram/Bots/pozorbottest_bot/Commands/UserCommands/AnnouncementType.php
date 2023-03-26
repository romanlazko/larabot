<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementType extends Command
{
    protected $name = 'type';

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
                array('Продать', 'product_count', 'sell'),
                array('Купить', 'photo', 'buy')
            ],
            [array('🏠 Главное меню', '/menu', '')]
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
