<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'title', 'artist', 'playtime', 'lyric'
    ];

}
