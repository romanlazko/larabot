<?php

namespace App\Telegram\Bots\pozorbottest;


class Config
{
    public static $config = [
        'inline_data'       => [
            'city'              => null,
            'type'              => null,
            'next'              => null,
            'condition'         => null,
            'category'          => null,
            'announcement_id'   => null,
        ],
        'brno_channel'      => '@pozor_baraholka',
        'prague_channel'    => '@baraholkaprague',
        'admin_ids'         => [
            
        ],
    ];

    
}