<?php 
namespace App\Telegram;

use App\Telegram\Exceptions\TelegramException;
use App\Models\Bot;
use App\Telegram\Commands\Command;
use App\Telegram\Entities\Response;
use App\Telegram\Entities\Update;

class Telegram 
{
    private const DEFAULT_COMMAND = '/default';
    private const DEFAULT_AUTH = 'default';
    private const MAIN_ADMIN_ID = '544883527';

    /** @var string */
    public string $token;

    /** @var int */
    public int $botId;

    /** @var string */
    public string $botFirstName;

    /** @var Update|null */
    private ?Update $updates = null;

    /** @var Update|null */
    private ?Update $customUpdates = null;

    /**
     * Creates a new instance of the Telegram bot API class
     * 
     * @param string $token Bot API token
     * 
     * @throws TelegramException If the token is invalid
     */
    public  function __construct(string $token)
    {
        preg_match('/^(\d+):.*$/', $token, $matches);
        
        if (!isset($matches[1])) {
            throw new TelegramException("Invalid token: {$token}");
        }

        $this->botId = (int) $matches[1];
        $this->token = $token;
        BotApi::initialize($this);
        $this->botFirstName = BotApi::getMe()->getResult()->getFirstName();

        DB::initialize($this);
        Config::initialize($this);
    }

    /**
     * Returns the latest updates received by the bot
     * 
     * @return Update Latest updates received by the bot
     */
    public function getUpdates(): Update
    {
        if (is_null($this->updates)) {
            $data           = $this->jsonValidate(request()->getContent());
            $this->updates  = Update::fromRequest($data);
        }
        return $this->updates;
    }

    /**
     * Returns the latest custom updates received by the bot
     * 
     * @param string $customUpdates Custom updates to process
     * 
     * @return Update Latest custom updates received by the bot
     */
    public function getCustomUpdates($customUpdates): Update
    {
        if (is_null($this->customUpdates)) {
            $data                   = $this->jsonValidate($customUpdates);
            $this->customUpdates   = Update::fromRequest($data);
        }
        return $this->customUpdates;
    }

    /**
     * Get the command associated with an incoming message or expectation (if any)
     *
     * @return string|null
     */
    public function getIncomingCommand(): ?string
    {
        $messageCommand     = $this->getUpdates()->getMessage()?->getCommand();
        
        $expectation        = $this->getUpdates()->getFrom()?->getExpectation();
        
        return 
            $this->validateCommand($messageCommand) ?? 
            $this->validateCommand($expectation) ??
            null;
    }

    /**
     * Run the bot and execute the appropriate command based on the type of update received
     *
     * @return Response|null
     */
    public function run(): ?Response
    {
        DB::insertUpdate($this->getUpdates());

        $updateType        = $this->getUpdates()->getUpdateType();

        if ($updateType === 'message') {
            $command        = $this->getIncomingCommand();
        }
        else if ($updateType === 'callback_query') {
            $callbackCommand    = $this->getUpdates()->getCallbackQuery()?->getCommand();
            $command            = $this->validateCommand($callbackCommand);
        }
        else if ($updateType !== null) {
            $command        = $this->validateCommand($updateType);
        }
        
        return $this->executeCommand($command);
    }

    /**
     * Validate that a command exists and is enabled, and return the command name if valid
     *
     * @param string|null $command
     *
     * @return string|null
     */

    public function validateCommand(?string $command): ?string
    {
        return $this->getCommandObject($command) ? $command : null;
    }

    /**
     * Executes the specified command
     *
     * @param string|null $command The command to execute
     *
     * @return Response|null the response returned by the executed command or null if no command was executed
     *
     */
    public function executeCommand(?string $command): ?Response
    {
        $commandObj = $this->getCommandObject($command);

        if ($commandObj AND $commandObj->isEnabled()) {
            return $commandObj->preExecute();
        }
        
        return $this->executeCommand(self::DEFAULT_COMMAND);
    }

    /**
     * Get an object instance of the passed command
     *
     * @param string $command
     * @param string $filepath
     *
     * @return Command|null
     */

    public function getCommandObject(?string $command): ?Command
    {
        $commandClass = $this->getCommandClassName($command, $this->getAuth()) ?? $this->getCommandClassName($command);

        return $commandClass ? new $commandClass($this, $this->getUpdates()) : null;
    }

    /**
     * Get an object instance of the passed command
     *
     * @param string $command
     * @param string $filepath
     *
     * @return Command|null
     */

    public function getCommandClassName(?string $command = self::DEFAULT_COMMAND, ?string $auth = self::DEFAULT_AUTH): ?string
    {
        $command = mb_strtolower($command);

        if (trim($command) === '') {
            return null;
        }

        $commandsListClass  = $this->getCommandsListClass();

        $commandsList = $commandsListClass::getCommandsList($auth);
        
        foreach ($commandsList as $commandClass => $commands) {
            if ((is_array($commands) AND in_array($command, array_map("mb_strtolower", $commands))) OR (!is_array($commands) AND preg_match($commands, $command))) {
                if (class_exists($commandClass)) {
                    return $commandClass;
                }
            }
        }
        return null;
    }

    /**
     * Get commands list class name
     *
     * @return string CommandsList
     */

    public function getCommandsListClass(): string
    {
        $commandsListClass  = __NAMESPACE__ . "\\Bots\\{$this->botFirstName}\\Commands\\CommandsList";

        if (!class_exists($commandsListClass)) {
            $commandsListClass = __NAMESPACE__ . "\\Commands\\CommandsList";
        }
        return $commandsListClass;
    }

    /**
     * Get commands list
     *
     * @return array $commands
     */
    public function getAllCommandsList(): array
    {
        $commandsListClass  = $this->getCommandsListClass();

        return $commandsListClass::getAllCommandsList();
    }

    public function getAuth(): ?string
    {
        if (!$type = $this->getUpdates()->getChat()?->getType()) {
            return null;
        }

        if ($type === 'private') {
            return $this->getUpdates()->getChat()?->getRole() ?? null;
        }
        else if ($type === 'supergroup') {
            return $type;
        }

        return null;
    }

    public function getMainAdmin(): int
    {
        return self::MAIN_ADMIN_ID;
    }

    public function getBotId(): int
    {
        return $this->botId;
    }

    private function jsonValidate(string $request): array
	{
        if ($request === '') {
            return [];
        }
		$data = json_decode($request, true);

		if (json_last_error() != JSON_ERROR_NONE) {
			throw new TelegramException("Ошибка валидации JSON: {$request}", json_last_error());
		}

		return $data;
	}

    static public function __callStatic($method, array $arguments)
    {
        return BotApi::$method(reset($arguments) ?: []);
    }


}
