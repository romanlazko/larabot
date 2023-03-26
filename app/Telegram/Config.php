<?php

namespace App\Telegram;

/**
 * Config class for App\Telegram namespace
 */
class Config 
{
    private static $config = [
        'inline_data' => [
            'button'    => null,
            'temp'      => null,
        ],
    ];

    /**
     * Initializes the Config class by merging the default config with the bot-specific config, if available
     * 
     * @param Telegram $telegram The Telegram instance
     *
     * @return void
     */

    public static function initialize(Telegram $telegram): void
    {
        $configsListClass  = __NAMESPACE__ . "\\Bots\\{$telegram->botUsername}\\Config";
        if (class_exists($configsListClass)) {
            foreach (self::$config as $key => $value) {
                if (array_key_exists($key, self::$config) AND array_key_exists($key, $configsListClass::$config)) {
                    if (is_array($value)) {
                        self::$config[$key] += $configsListClass::$config[$key];
                    }else{
                        self::$config[$key] = $configsListClass::$config[$key];
                    }
                }
            }
            self::$config += $configsListClass::$config;
        }
    }

    /**
     * Returns the value of the specified key from the default config array
     * 
     * @param string $key The key to retrieve from the default config array
     *
     * @return mixed The value of the specified key from the default config array
     */

    public static function get(string $key): mixed
    {
        return self::$config[$key] ?? null;
    }
}
