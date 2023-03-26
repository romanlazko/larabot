<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\AdminCommands;

use App\Telegram\BotApi;
use App\Telegram\Bots\pozorbottest\Models\Announcement;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class MenuCommand extends Command
{
    protected $name = 'menu';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcements = Announcement::where('status', 'new')->paginate(10);

        $buttons = [];
        foreach ($announcements as $announcement) {
            $buttons[] = [array($announcement->title ?? $announcement->caption, 'show_announcement', $announcement->id)];
        }

        $buttons = count($buttons) > 0 ? BotApi::inlineKeyboard($buttons, 'announcement_id') : null;  

        return BotApi::returnInline([
            'text'          => "Все объявления: {$announcements->count()}",
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            'reply_markup'  => $buttons,
        ]);
    }
}
