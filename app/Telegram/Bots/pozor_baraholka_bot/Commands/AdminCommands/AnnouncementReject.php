<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\AdminCommands;

use App\Telegram\BotApi;
use App\Telegram\Bots\pozor_baraholka_bot\Models\Announcement;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;
use App\Telegram\Exceptions\TelegramException;
use App\Telegram\Exceptions\TelegramUserException;

class AnnouncementReject extends Command
{
    public static $command = 'reject';

    public static $title = 'Отклонить';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcement = Announcement::findOr($updates->getInlineData()?->getAnnouncementId(), function () {
            throw new TelegramUserException("Объявление не найдено");
        });

        if ($announcement->status === 'published' OR $announcement->status === 'irrelevant') {
            throw new TelegramUserException("Объявление уже опубликовано");
        }

        $announcement->update([
            'status' => 'rejected'
        ]);
        
        $this->notifyRejectedAd($announcement);

        return $this->bot->executeCommand(MenuCommand::$command);
    }

    private function notifyRejectedAd(Announcement $announcement)
    {
        try {
            return BotApi::sendMessage([
                'chat_id'       => $announcement->user_id,
                'text'          => "Ваше объявление отклонено", 
                'parse_mode'    => 'HTML',
            ]);
        }
        catch (TelegramException $exception) {
            throw new TelegramUserException("Ошибка отправки уведомления пользователю: {$exception->getMessage()}");
        }
    }
}
