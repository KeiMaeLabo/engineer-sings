<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SongRequest;
use App\Song;

class SongController extends Controller
{
    public function index()
    {

    }

    public function getSongList()
    {
        $songs = Song::orderBy('title', 'asc')->get();
        return $songs;
    }

    public function store(SongRequest $request)
    {
        if ($request->id == '') {
            $song = new Song;
            $song->fill($request->all())->save();
            return [ 'message' => 'registered'];
        } else {
            $song = Song::find($request->id);
            $song->fill($request->all())->update();
            return [ 'message' => 'updated'];
        }
    }
}
