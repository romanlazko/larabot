<?php
namespace App\Telegram\Entities;

use App\Telegram\DB;
use App\Telegram\Entities\BaseEntity;

class User extends BaseEntity
{
    public $expectation = null;

    public static $map = [
        'id'                            => true,
        'is_bot'	                    => true,
        'first_name'	                => true,
        'last_name'	                    => true,
        'username'	                    => true,
        'language_code'	                => true,
        'is_premium'	                => true,
        'added_to_attachment_menu'	    => true,
        'can_join_groups'	            => true,
        'can_read_all_group_messages'	=> true,
        'supports_inline_queries'       => true,
    ];

    public function getExpectation()
    {
        if ($this->expectation === null) {
            $this->expectation = DB::getExpectation($this->getId());
        }
        return $this->expectation;
        
    }

    public function setExpectation($expectation = null)
    {
        $this->expectation = DB::setExpectation($this->getId(), $expectation);
    }
}