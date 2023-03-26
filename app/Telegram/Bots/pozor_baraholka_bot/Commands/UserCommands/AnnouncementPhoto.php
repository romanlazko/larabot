<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementPhoto extends Command
{
    public static $command = 'photo';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $type           = $updates->getInlineData()->getType();
        $next           = $updates->getInlineData()->getNext() ?: 'title';
        
        $conversation->update([
            'type' => $conversation->notes['type'] ?? $type,
            'next' => $conversation->notes['next'] ?? $next,
        ]);

        $trade = $conversation->notes['type'] === 'buy' ? 'купить' : 'продать';

        $updates->getFrom()->setExpectation('photo|1');

        $buttons = BotApi::inlineKeyboard([
            [array('Без фотографий', AnnouncementNext::$command, '')],
            [array(MenuCommand::$title, MenuCommand::$command, '')]
        ]);

        $data = [
            'text'          =>  "Пришли мне *фотографии* товара который ты хочешь {$trade}, *максимально 9 фото*."."\n\n".
                                "_Если фотографий нет, нажми_ *'Без фотографий'*.",
            'reply_markup'  =>  $buttons,
            'chat_id'       =>  $updates->getChat()->getId(),
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'parse_mode'    =>  "Markdown"
        ];     
                        
        return BotApi::returnInline($data);
    }
}
