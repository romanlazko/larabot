<?php

namespace App\Telegram\Bots\pozor_baraholka_bot;


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
        'brno'      => '@pozor_baraholka',
        'prague'    => '@baraholkaprague',
        'admin_ids'         => [
            
        ],
    ];

    
}
