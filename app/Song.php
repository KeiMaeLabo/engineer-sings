<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'id', 'title', 'artist', 'playtime', 'lyric'
    ];

}
