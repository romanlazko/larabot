<?php 

namespace App\Telegram\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Telegram\BotApi;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class AwaitPhoto extends Command
{
    public static $command = '/photo\|\d/';

    public static $title = '';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        return $this->savePhoto(function(int $photoCount) use($updates){
            
            $buttons = BotApi::inlineKeyboard([
                [array('Готово', AnnouncementNext::$command, '')],
                [array(MenuCommand::$title, MenuCommand::$command, '')]
            ]);
        
            $data = [
                'text'          =>  "*Фото {$photoCount}/9 сохранено*"."\n\n".
                                    "_Как только все фото будут сохранены, нажми_ *'Готово'*.",

                'chat_id'       =>  $updates->getChat()->getId(),
                'reply_markup'  =>  $buttons,
                'parse_mode'    =>  "Markdown",
                'message_id'    =>  $updates->getChat()->getLastMessageId()
            ];
        
            return $photoCount > 1 
                ? BotApi::editMessageText($data) 
                : BotApi::sendMessage($data);
        });
    }

    public function savePhoto(\Closure $action): Response
    {
        $user           = $this->updates->getFrom();
        $conversation   = $this->getConversation();

        list($expectationType, $expectationValue) = explode('|', $user->getExpectation());
        
        if (!$photo = $this->updates->getMessage()?->getPhoto()) {
            $conversation->unsetNote($expectationType);
            $this->handleError("Один из присланных файлов не является фотографией.");
            return $this->bot->executeCommand(AnnouncementPhoto::$command);
        }

        if ($expectationValue > 9) {
            $conversation->unsetNote($expectationType);
            $this->handleError("Слишком много фотографий.");
            return $this->bot->executeCommand(AnnouncementPhoto::$command);
        }
    
        $conversation->update([
            $expectationType => $conversation->notes[$expectationType][$expectationValue] = $photo
        ]);

        $user->setExpectation($expectationType . '|' . ($expectationValue + 1));
    
        return $action($expectationValue);
    }
}
