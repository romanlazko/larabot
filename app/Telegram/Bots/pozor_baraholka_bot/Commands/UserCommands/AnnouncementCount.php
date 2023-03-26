<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementCount extends Command
{
    public static $command = 'product_count';

    public static $title = '';

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
                array('Один', AnnouncementPhoto::$command, 'title'),
                array('Несколько', AnnouncementPhoto::$command, 'product')
            ],
            [array(MenuCommand::$title, MenuCommand::$command, '')]
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
