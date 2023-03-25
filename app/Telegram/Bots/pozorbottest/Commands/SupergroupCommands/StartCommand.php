<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\SupergroupCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class StartCommand extends Command
{
    protected $name = 'start';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array('Главное меню', 'menu', '')],
        ]);
        return BotApi::returnInline([
            'text'              => "Это меню супер группы",
            'chat_id'           => $updates->getChat()->getId(),
            'reply_markup'      => $buttons,
            'message_id'        => $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            'message_thread_id'  => $updates->getCallbackQuery()?->getMessage()->getMessageThreadId() ?? $updates->getMessage()->getMessageThreadId(),
        ]);
    }
}
