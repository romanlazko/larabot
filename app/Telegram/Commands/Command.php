<?php 

namespace App\Telegram\Commands;

use App\Telegram\Exceptions\TelegramException;
use App\Telegram\BotApi;
use App\Telegram\Conversation;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;
use App\Telegram\Exceptions\TelegramMainAdminException;
use App\Telegram\Exceptions\TelegramUserException;
use App\Telegram\Telegram;

abstract class Command 
{
    protected $name;

    protected $enabled;

    protected $bot;
    
    protected $updates;

    protected $conversation = null;

    public function __construct(Telegram $bot, Update $updates)
    {
        $this->bot      = $bot;
        $this->updates  = $updates;
    }

    public function preExecute()
    {
        try{
            return $this->execute($this->updates);
        }
        catch( TelegramUserException $exception ){
            $this->handleError($exception->getMessage());
            return $this->bot->executeCommand('/menu');
        }
    }

    abstract public function execute(Update $updates);

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function handleError(string $text): ?Response
    {
        return BotApi::sendMessage([
            'text'          => $text,
            'chat_id'       => $this->updates->getChat()->getId(),
            'parse_mode'    => "Markdown"
        ]);
    }

    public function handleMainAdminError(TelegramException $exception): ?Response
    {
        $text = [];

        $text[] = "Description: ".$exception->getMessage();
        $text[] = "Code: ".$exception->getCode();
        $text[] = "Params: ".$exception->getParamsAsJson();
        $text[] = "File: ".$exception->getFile()." -> ".$exception->getLine();

        return BotApi::sendMessage([
            'text'          => implode("\n\n", $text),
            'chat_id'       => $this->bot->getMainAdmin(),
        ]);
    }

    protected function getConversation(int $user_id = null)
    {
        if ($this->conversation === null) {
            $user_id = $this->updates->getFrom()->getId($user_id);
            $this->conversation = new Conversation($user_id);
        }
        return $this->conversation;
    }

    protected function getUserContact(string $parse_mode = 'Markdown', int $chat_id = null): string
    {
        if ($chat_id === null) {
            $chat = $this->updates->getChat();
        }else{
            $chat = BotApi::getChat([
                'chat_id' => $chat_id
            ])->getResult();
        }

        if ($chat->getUsername()) {
            return "@{$chat->getUsername()}";
        } else {
            if ($parse_mode === 'HTML') {
                return "<a href='tg://user?id={$chat->getId()}'>{$chat->getFirstName()} {$chat->getLastName()}</a>";
            }
            return "[tg://user?id={$chat->getId()}]({$chat->getFirstName()} {$chat->getLastName()})";
        }
    }

    





}
