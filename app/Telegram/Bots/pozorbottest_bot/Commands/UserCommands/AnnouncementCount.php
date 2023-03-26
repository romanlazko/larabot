<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementCount extends Command
{
    protected $name = 'product_count';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $type           = $updates->getInlineData()->getType();
        
        $conversation->update([
            'type' => $type,
        ]);
            
        return $this->sendResponse($updates);
    }

    private function sendResponse(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [
                array('Один', 'photo', 'title'),
                array('Несколько', 'photo', 'product')
            ],
            [array('🏠 Главное меню', '/menu', '')]
        ], 'next');

        $data = [
            'text'          => "Сколько товаров будет в объявлении?",
            'reply_markup'  => $buttons,
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()->getMessage()->getMessageId(),
            'parse_mode'    => "Markdown"
        ];
                
        return BotApi::editMessageText($data);
    }
}
