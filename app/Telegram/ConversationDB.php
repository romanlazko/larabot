<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Telegram;

use App\Telegram\Exceptions\TelegramException;
use App\Telegram\Models\TelegramConversation;
use Exception;
use Throwable;

class ConversationDB extends DB
{
    

    /**
     * Select a conversation from the DB
     *
     * @param int $user_id
     * @param int $chat_id
     * @param int $limit
     *
     * @return TelegramConversation|null
     * @throws TelegramException
     */
    public static function selectConversation(int $user_id): ?TelegramConversation
    {
        try {
            return self::getUser($user_id)->conversation()->first();
        } catch (Throwable $exception) {
            throw new TelegramException($exception->getMessage());
        }
    }

    /**
     * Insert the conversation in the database
     *
     * @param int    $user_id
     * @param int    $chat_id
     *
     * @return TelegramConversation
     * @throws TelegramException
     */
    public static function insertConversation(int $user_id): ?TelegramConversation
    {
        try {
            return self::getUser($user_id)->conversation()->create();
        } catch (Throwable $exception) {
            throw new TelegramException($exception->getMessage());
        }
    }

    /**
     * Update a specific conversation
     *
     * @param array $fields_values
     * @param array $where_fields_values
     *
     * @return bool
     * @throws TelegramException
     */
    public static function updateConversation(array $fields_values, int $user_id): bool
    {
        try {
            return self::getUser($user_id)->conversation()->update($fields_values);
        } catch (Throwable $exception) {
            throw new TelegramException($exception->getMessage());
        }
    }
}