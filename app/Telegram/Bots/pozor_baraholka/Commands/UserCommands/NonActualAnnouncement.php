<?php 

namespace App\Telegram\Bots\pozorbottest\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Bots\pozorbottest\Models\Announcement;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;
use App\Telegram\Exceptions\TelegramUserException;

class NonActualAnnouncement extends Command
{
    protected $name = 'menu';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $announcement = Announcement::findOr($updates->getInlineData()?->getAnnouncementId(), function () {
            throw new TelegramUserException("Объявление не найдено");
        });

        $announcement->update([
            'status' => 'non_actual'
        ]);

        BotApi::answerCallbackQuery([
            'callback_query_id' => $updates->getCallbackQuery()->getId(),
            'text'              => "Вам больше не смогут писать по поводу этого объявления",
            'show_alert'        => true
        ]);

        return $this->bot->executeCommand('/menu');
    }
    
    
}