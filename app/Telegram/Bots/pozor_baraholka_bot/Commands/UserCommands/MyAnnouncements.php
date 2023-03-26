<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Bots\pozorbottest\Models\Announcement;
use App\Telegram\Commands\Command;
use App\Telegram\Config;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class MyAnnouncements extends Command
{
    public static $command = 'my_announcements';

    public static $title = 'Мои объявления';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $user_id = $updates->getFrom()->getId();
        $announcements = Announcement::where([
            'user_id' => $user_id,
            'status' => 'published',
        ])->paginate(10);

        $buttons = [];
        foreach ($announcements as $announcement) {
            $buttons[] = [array($announcement->title ?? $announcement->caption, ShowMyAnnouncement::$command, $announcement->id)];
        }

        if (count($buttons) === 0) {
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text'              => "У Вас еще нет ни одного объявления",
                'show_alert'        => true
            ]);
        }
        
        $buttons = BotApi::inlineKeyboard($buttons, 'announcement_id');

        $data = [
            'text'          => "Мои объявления",
            'reply_markup'  => $buttons,
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()->getMessage()->getMessageId(),
            'parse_mode'    => "Markdown"
        ];
                                
        return BotApi::editMessageText($data);
    }
}
