<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\AdminCommands;

use App\Telegram\BotApi;
use App\Telegram\Bots\pozor_baraholka_bot\Models\Announcement;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class MenuCommand extends Command
{
    public static $command = '/menu';

    public static $title = 'Все объявления';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcements = Announcement::where('status', 'new')->paginate(10);

        $buttons = [];
        foreach ($announcements as $announcement) {
            $buttons[] = [array($announcement->title ?? $announcement->caption, AnnouncementShow::$command, $announcement->id)];
        }

        $buttons = count($buttons) > 0 ? BotApi::inlineKeyboard($buttons, 'announcement_id') : null;  

        return BotApi::returnInline([
            'text'          => "Все объявления: ". Announcement::where('status', 'new')->count(),
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()?->getMessage()->getMessageId(),
            'reply_markup'  => $buttons,
        ]);
    }
}
