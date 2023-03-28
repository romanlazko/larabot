<?php 
namespace App\Telegram;

use App\Telegram\Entities\Response;
use App\Telegram\Exceptions\TelegramException;
use App\Telegram\Models\TelegramMessage;

class BotApi 
{

    static $telegram;

	static $proxySettings = [];

	static $curl;

	static $customCurlOptions = [];

	static $returnArray = true;

    static $current_action = '';

	const URL_PREFIX = 'https://api.telegram.org/bot';

	const DEFAULT_STATUS_CODE = 200;

    const NOT_MODIFIED_STATUS_CODE = 304;

    static $actions_need_to_save = [
        'sendMessage',
        'editMessageText',
        'sendMediaGroup',
		'sendPhoto',
    ];

	static $actions_need_delete_reply_markup = [
		'sendMessage',
        'sendMediaGroup',
		'sendPhoto',
	];

    static public function initialize(Telegram $telegram){
        self::$telegram = $telegram;
    }

	static function sendMessages($data): array
    {
		$results = [];
        foreach ($data['chat_ids'] as $chat_id) {
			try{
				$data = [
					'text'          => $data['text'],
					'chat_id'       => $chat_id,
					'parse_mode'    => $data['parse_mode'],
					'reply_markup'  => $data['reply_markup'],
				];
				$results[$chat_id] = self::call('sendMessage', $data)->getOk();
			}
			catch(TelegramException $exception){
				$results[$chat_id] = $exception->getMessage();
			}
		}
		return $results;
    }

    static function returnInline(array $data): ?Response
    {
        if (self::$telegram->getUpdates()->getCallbackQuery()) {
            return self::editMessageText($data);
        }
        return self::sendMessage($data);
    }

    static function setInputMediaPhoto($photos = [], string $caption = "", string $parse_mode = "Markdown"): string
    {
        foreach ($photos as $key => $file_id) {
			if($file_id != '' AND $file_id != '0' AND !empty($file_id)){
				$media[] = ['type' => 'photo', 'media' => $file_id];
			}
		}
		$media[array_key_last($media)]['caption']     = $caption;
		$media[array_key_last($media)]['parse_mode']  = $parse_mode;
        return json_encode($media);
    }

	static function getPhoto(array $data)
	{
		if ($data['file_id']) {
			$file_path = self::getFile($data)->getResult()->getFilePath();
			return "https://api.telegram.org/file/bot".self::$telegram->token."/{$file_path}";
		}
		return "https://via.placeholder.com/150";
	}

    /**
     * Return an empty Response
     *
     * No request is sent to Telegram.
     * This function is used in commands that don't need to fire a message after execution
     *
     * @return Response
     */
    public static function emptyResponse(): Response
    {
        return Response::fromRequest(['ok' => true]);
    }

    /**
     * Return an bad empty Response
     *
     * No request is sent to Telegram.
     * This function is used in commands that don't need to fire a message after execution
     *
     * @return Response
     */
    public static function emptyBadResponse(): Response
    {
        return Response::fromRequest(['ok' => false]);
    }

    static public function escapeMarkdown(string $text): string
    {
        if (
            (is_string($text) AND is_array(json_decode($text, true))) OR 
            !(
                (substr_count($text, '[') == substr_count($text, ']')) AND
                (substr_count($text, '_') % 2 === 0 ) AND
                (substr_count($text, '*') % 2 === 0 ) AND
                (substr_count($text, '`') % 2 === 0 )
            )
        ){
            return str_replace(
                ['[', '`', '*', '_',],
                ['\[', '\`', '\*', '\_',],
                $text
            );
        }else {
            return $text;
        }
    }

    static public function Keyboard(array $buttons): string
    {
        return json_encode(array(
			'keyboard' => $buttons,
	        'resize_keyboard' => true,
	        'one_time_keyboard' => false
	    ), JSON_UNESCAPED_UNICODE);
    }

    static public function inlineKeyboard(array $buttons, string $step = '', array $link = null): array
	{
        $inline_data = self::$telegram->getUpdates()->getInlineData()->asArray();
		$vertical_Buttons = [];
		foreach ($buttons as $key => $vertical) {
			$horizontal_Buttons = [];
			foreach ($vertical as $key => $horizontal) {

				$button_text 			= $horizontal[0];
				$inline_data['button'] 	= $horizontal[1];
				$inline_data[$step]	 	= $horizontal[2];

				$horizontal_Buttons[] = array(
					'text'           => $button_text,
					'callback_data'  => implode('|', $inline_data)
				);
			}
			$vertical_Buttons[]	= $horizontal_Buttons;
		}
		if (!is_null($link)) {
			array_unshift(
				$vertical_Buttons,
				$link
			);
		}
		return $vertical_Buttons;
	}

    static public function inlineKeyboardWithLink(array $link, array $buttons = null, string $step = ''): array
	{
        if ($buttons === null) {
            return [[$link]];
        }
        return self::inlineKeyboard($buttons, $step, [$link]);
	}

    static public function inlineCheckbox(array $buttons, string $step = ''): array
	{
		$inline_data 		= self::$telegram->getUpdates()->getInlineData()->asArray();
		$inline_data_step 	= explode(':', $inline_data[$step]);
				
		if(in_array($inline_data['temp'], $inline_data_step)){
			unset($inline_data_step[array_search($inline_data['temp'], $inline_data_step)]);
		}
		else{
			array_push($inline_data_step, $inline_data['temp']);
		}
		
		foreach ($buttons as $key => $vertical) {
			$horizontal_Buttons = [];
			foreach ($vertical as $key => $horizontal) {
				$button_text 				= $horizontal[0];
				$inline_data['button'] 		= $horizontal[1];
				$inline_data['temp']	    = $horizontal[2];
				
				if($horizontal[2] != ''){
					if(in_array($inline_data['temp'], $inline_data_step)){
						$button_text .= hex2bin('E29C85');
					}
					else {
						$button_text .= hex2bin('E29D8C');
					}
				}
				$inline_data[$step] 		= implode(':', $inline_data_step);
				
				$horizontal_Buttons[] = array(
					'text'           => $button_text,
					'callback_data'  => implode('|', $inline_data)
				);
			}
			$vertical_Buttons[]	= $horizontal_Buttons;
		}

		return $vertical_Buttons;
	}

    public static function getCurrentAction(): string
    {
        return static::$current_action;
    }

    static public function __callStatic($apiMethod, $data): ?Response
    {
		self::deleteReplyMarkup($apiMethod, reset($data) ?: []);

        return self::call($apiMethod, reset($data) ?: []);
    }

	static private function deleteReplyMarkup($apiMethod, array $data): void
	{
		if (in_array($apiMethod, static::$actions_need_delete_reply_markup)) {
			try{
				$chat = self::getChat([
					'chat_id' => $data['chat_id'],
				])->getResult();
	
				self::editMessageReplyMarkup([
					'chat_id'       => $chat->getId(),
					'message_id'    => $chat->getLastMessageId(),
				]);
			}catch(TelegramException $e){
				
			}
		}
	}

    static public function call($apiMethod, array $params = null): ?Response
  	{
		static::$current_action = $apiMethod;

        if (isset($params['reply_markup']) AND is_array($params['reply_markup'])) {
            $params['reply_markup'] = json_encode(array("inline_keyboard" => $params['reply_markup']), JSON_UNESCAPED_UNICODE);
        }

		$options = static::$proxySettings + [
			CURLOPT_URL => self::getUrl().'/'.$apiMethod,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => null,
			CURLOPT_POSTFIELDS => null,
			CURLOPT_TIMEOUT => 5,
		];

		if ($params) {
			$options[CURLOPT_POST] = true;
			$options[CURLOPT_POSTFIELDS] = $params;
		}

		if (!empty(static::$customCurlOptions) && is_array(static::$customCurlOptions)) {
			$options = static::$customCurlOptions + $options;
		}

		$response = Response::fromRequest(self::jsonValidate(self::executeCurl($options, $params)));

        if ($result = $response->getResult() AND in_array($apiMethod, static::$actions_need_to_save)) {
            if (is_array($result)) {
                foreach ($result as $key => $message) {
                    DB::insertMessageRequest($message);
                }
            }
            else if ($result->getMessageId()) {
                DB::insertMessageRequest($result);
            }
        }
        
        return $response;
	}

	static function executeCurl(array $options, array $params)
	{
        $curl = curl_init();
		curl_setopt_array($curl, $options);

		$result = curl_exec($curl);
		self::curlValidate($curl, $result, $params);
		if ($result === false) {
			throw new TelegramException(curl_error($curl), curl_errno($curl), $options);
		}

		return $result;
	}

	static function curlValidate($curl, $response = null, $params = null): void
	{
		if (($httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE))
			&& !in_array($httpCode, [self::DEFAULT_STATUS_CODE, self::NOT_MODIFIED_STATUS_CODE])
		) {
			throw new TelegramException($response, $httpCode, $params);
		}
	}

	static function jsonValidate($jsonString, $asArray = true)
	{
		$json = json_decode($jsonString, $asArray);

		if (json_last_error() != JSON_ERROR_NONE) {
			throw new TelegramException("Ошибка валидации JSON: {$jsonString}", json_last_error());
		}

		return $json;
	}

	static function getUrl(): string
	{
		return self::URL_PREFIX.self::$telegram->token;
	}
}
