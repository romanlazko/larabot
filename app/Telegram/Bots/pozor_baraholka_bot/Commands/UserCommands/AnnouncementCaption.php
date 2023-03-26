<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementCaption extends Command
{
    public static $command = 'caption';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $conversation   = $this->getConversation();
        $category       = $updates->getInlineData()->getCategory();
        $text           = $this->createText($conversation->notes);    

        if (!$category) {
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text'              => "Нужно выбрать хотя бы одну категорию",
                'show_alert'        => true
            ]);
        }
        
        $conversation->update([
            'category' => $category
        ]);
            
        return $this->sendResponse($updates, $text);
    }

    private function sendResponse(Update $updates, string $text)
    {
        $updates->getFrom()->setExpectation(AwaitCaption::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array(MenuCommand::$title, MenuCommand::$command, '')]
        ]);

        $data = [
            'text'          => $text,
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()->getMessage()->getMessageId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons
        ];

        return BotApi::editMessageText($data);
    }

    private function createText(array $notes): string
    {
        $trade  = $notes['type'] === 'buy' ? 'купить' : 'продать';
        $text   = $notes['next'] === 'title'
            ?   "Напиши *описание* товара, который ты хочешь *{$trade}*.". "\n\n" ."_Максимально_ *800* _символов, без эмодзи_."
            :   "Напиши построчно *описания товаров* на продажу и их стоимость."."\n\n".
                "*Пример*:"."\n". 
                "1) Футболка - 100 крон,"."\n".
                "2) Куртка - 250 крон."."\n\n".
                "_Максимально_ *800* _символов, без эмодзи._";

        return $text;
    }
    




}
