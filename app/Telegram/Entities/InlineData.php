<?php
namespace App\Telegram\Entities;

use App\Telegram\Config;
use App\Telegram\Entities\BaseEntity;

class InlineData extends BaseEntity
{
    public $inline_data = [];

    public static function fromRequest($data = '')
    {
        $data = explode('|', $data);
        $instance = new static();
        $instance->map($data);

        return $instance;
        
    }

    public function map(array $data): void
    {
        $i=0;
        $map = Config::get('inline_data');
        
        foreach ($map as $key => $value) {
            if (isset($data[$i])) {
                $this->inline_data[$key] = $data[$i];
            }
            else{
                $this->inline_data[$key] = null;
            }
            $i++;
        }
    }

    public function asArray()
    {
        return $this->inline_data;
    }

    public function __call($method, $args)
    {
        $property_name = mb_strtolower(ltrim(preg_replace('/[A-Z]/', '_$0', substr($method, 3)), '_'));

        if (isset($this->inline_data[$property_name])) {
            if(isset($args) AND count($args) == 1 AND !in_array(null, $args)){
                $this->inline_data[$property_name] = $args[0];
            }
            return $this->inline_data[$property_name];
        }

    }
}