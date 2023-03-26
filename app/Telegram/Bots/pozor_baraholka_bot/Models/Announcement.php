<?php

namespace App\Telegram\Bots\pozor_baraholka_bot\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function photo()
    {
        return $this->hasMany(AnnouncementPhoto::class, 'announcement_id', 'id');
    }
}
