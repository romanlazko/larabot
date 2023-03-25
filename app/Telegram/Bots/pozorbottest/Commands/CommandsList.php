<?php
namespace App\Telegram\Bots\pozorbottest\Commands;

use App\Telegram\Commands\CommandsList as DefaultCommandsList;

class CommandsList extends DefaultCommandsList
{
    static private $commands = [
        'admin'     => [
            AdminCommands\StartCommand::class           => ['/start', 'start'],
            AdminCommands\MenuCommand::class            => ['/menu', 'menu', 'Главное меню'],
            AdminCommands\AnnouncementShow::class       => ['show_announcement'],
            AdminCommands\AnnouncementPublic::class     => ['public'],
            UserCommands\GetOwnerContact::class         => "/^(\/start\s)(announcement)=(\d+)$/",
        ],
        'user'      => [
            UserCommands\StartCommand::class            => ['/start', 'start'],
            UserCommands\MenuCommand::class             => ['/menu', 'menu', 'Главное меню'],
            UserCommands\NewAnnouncement::class         => ['new_announcement'],
            UserCommands\AnnouncementType::class        => ['type'],
            UserCommands\AnnouncementCount::class       => ['product_count'],
            UserCommands\AnnouncementPhoto::class       => ['photo'],
            UserCommands\AnnouncementNext::class        => ['next'],
            UserCommands\AnnouncementTitle::class       => ['title'],
            UserCommands\AnnouncementCost::class        => ['cost'],
            UserCommands\AnnouncementCondition::class   => ['condition'],
            UserCommands\AnnouncementProduct::class     => ['product'],
            UserCommands\AnnouncementCategory::class    => ['category'],
            UserCommands\AnnouncementCaption::class     => ['caption'],
            UserCommands\AnnouncementShow::class        => ['show'],
            UserCommands\AnnouncementPublic::class      => ['public'],
            UserCommands\AwaitPhoto::class              => "/photo\|\d/",
            UserCommands\AwaitTitle::class              => "/^await_title$/",
            UserCommands\AwaitCost::class               => "/^await_cost$/",
            UserCommands\AwaitCaption::class            => "/^await_caption$/",
            UserCommands\GetOwnerContact::class         => "/^(\/start\s)(announcement)=(\d+)$/",
            
            UserCommands\MyAnnouncements::class         => ['my_announcements'],
            UserCommands\ShowMyAnnouncement::class      => ['show_announcement'],
            UserCommands\NonActualAnnouncement::class   => ['non_actual'],
        ],
        'wishfull' => [
            SupergroupCommands\DaCommand::class         => ['Да'],
        ],
        'supergroup' => [
            SupergroupCommands\StartCommand::class      => ['/start'],
            SupergroupCommands\DaCommand::class         => ['Да'],
        ],
        'default'   => [

        ]
    ];

    static public function getCommandsList(?string $auth)
    {
        // if ($auth AND isset(self::$commands[$auth])) {
        //     return array_merge(self::$commands[$auth], self::$default_commands[$auth] ?? []);
        // }
        // return self::getCommandsList('default');
        if ($auth) {
            $commands = self::$commands[$auth] ?? [];
            $default_commands = self::$default_commands[$auth] ?? [];
            return $commands + $default_commands;
        }
        return self::getCommandsList('default');
        
    }

    static public function getAllCommandsList()
    {
        foreach (self::$commands as $auth => $commands) {
            $commands_list[$auth] = self::getCommandsList($auth);
        }
        return $commands_list;
    }
}
