<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AnnouncementTitle extends Command
{
    public static $command = 'title';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $type   = $this->getConversation()->notes['type'];
        $trade  = $type === 'buy' ? 'купить' : 'продать';

        $updates->getFrom()->setExpectation(AwaitTitle::$expectation);

        $buttons = BotApi::inlineKeyboard([
            [array(MenuCommand::$title, MenuCommand::$command, '')]
        ]);

        $data = [
            'text'          => "Напиши *заголовок* к товару, который ты хочешь *{$trade}*."."\n\n"."_Максимально_ *30* _символов, без эмодзи_.",
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons
        ];

        return BotApi::returnInline($data);
    }




}
