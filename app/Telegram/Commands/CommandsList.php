<?php
namespace App\Telegram\Commands;


class CommandsList 
{
    static protected $default_commands = [
        'admin'     => [

        ],
        'user'      => [
            UserCommands\StartCommand::class                => ['/start'],
            UserCommands\HelpCommand::class                 => ['/help'],

            /** Send empty response, in case the transmitted command is not on this list, return empty Response **/
            DefaultCommands\EmptyResponseCommand::class     => ['/default'],
            
            /** Send text "It is default command", in case the transmitted command is not on this list, return Default text **/
            // DefaultCommands\DefaultCommand::class           => ['/default'],

            /** Send update like pretty string, in case the transmitted command is not on this list, return Update like string **/
            // DefaultCommands\SendResultCommand::class        => ['/default'],
        ],
        'supergroup' => [
            /** Send empty response, in case the transmitted command is not on this list, return empty Response **/
            DefaultCommands\EmptyResponseCommand::class     => ['/default'],
            
            /** Send text "It is default command", in case the transmitted command is not on this list, return Default text **/
            // DefaultCommands\DefaultCommand::class           => ['/default'],

            /** Send update like pretty string, in case the transmitted command is not on this list, return Update like string **/
            // DefaultCommands\SendResultCommand::class        => ['/default'],
        ],
        'default'   => [
            /** Send text "It is default command", in case the transmitted command is not on this list, return Default text **/
            // DefaultCommands\DefaultCommand::class           => ['/default'],

            /** Send update like pretty string, in case the transmitted command is not on this list, return Update like string **/
            // DefaultCommands\SendResultCommand::class        => ['/default'],

            DefaultCommands\EmptyResponseCommand::class     => [
                '/default',
                'channel_post', 
                'edited_message', 
                'edited_channel_post', 
                'inline_query', 
                'chosen_inline_result', 
                'shipping_query',
                'pre_checkout_query',
                'poll',
                'poll_answer',
                'my_chat_member',
                'chat_member',
                'chat_join_request',
            ],
        ]
        
    ];

    static public function getCommandsList(?string $auth)
    {
        if ($auth AND isset(self::$default_commands[$auth])) {
            return self::$default_commands[$auth];
        }
        return self::$default_commands['default'];
    }

    static public function getAllCommandsList()
    {
        foreach (self::$default_commands as $auth => $commands) {
            $commands_list[$auth] = self::getCommandsList($auth);
        }
        return $commands_list;
    }
}
