<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SongRequest;
use App\Song;
use GuzzleHttp\Client;

class SongController extends Controller
{
    public function index()
    {
        return view('main');
    }

    public function getSongList()
    {
        $songs = Song::orderBy('title', 'asc')->get();
        return $songs;
    }

    public function store(SongRequest $request)
    {
        if ($request->id == 'new') {
            $song = new Song;
            $song->title = $request->title;
            $song->artist = $request->artist;
            $song->playtime = $request->playtime;
            $song->lyric = $request->lyric;
            $song->save();
            return [ 'message' => 'registered'];
        } else {
            $song = Song::find($request->id);
            $song->fill($request->all())->update();
            return [ 'message' => 'updated'];
        }
    }

    public function getLyric(Request $request)
    {
        $client = new Client();
        $options = [
            'headers' => [
                'content-type' => 'application/json',
                'content-language' => 'ja',
                'access-token' => 'QZKGUP7MGZEV17PMORI9',
                'secret-key' => '9BIqptgrHGORNh2QEaianZn8ms1eO01VMdGWio0e',
            ],
            'json' => [
                'timeout' => 120000,
                'callback_endpoint' => null,
                'callback_tries' => 1,
                'input' => [
                    'artist' => $request->artist,
                    'title' => $request->title,
                ]
            ]
        ];
        $response = $client->request('POST', 'https://api.c-bot.pro/keimaelabo/bots/getlyrics/jobs', $options);
        $result = $response->getBody();
        return $result;
    }
}
