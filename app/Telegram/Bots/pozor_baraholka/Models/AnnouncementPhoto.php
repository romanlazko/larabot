<?php

namespace App\Telegram\Bots\pozorbottest\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnnouncementPhoto extends Model
{
    use HasFactory; use SoftDeletes;

    protected $fillable = [
        'file_id'
    ];
}
