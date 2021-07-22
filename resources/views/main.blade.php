<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <script src="{{ asset('js/app.js') }}"></script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/main.css') }}" rel="stylesheet">
        <style>

        </style>
    </head>
    <body>
        <div id="app">
            <div class="container-fluid d-flex p-0">
                <div class="left-content">
                    <label for="input-video" class="buttons"><i class="fas fa-video"></i></label>
                    <input id="input-video" class="d-none" type="file" accept="video/*" v-on:change="handleFileSelect">
                    <button type="button" id="lyric" class="buttons" data-toggle="modal" data-target="#songModal" v-on:click="getSongList"><i class="fas fa-edit"></i></button>
                </div>
                <div class="main-content">
                    <video controls v-if="src" muted>
                        <source :src="src" type='video/mp4; codecs="avc1.64001E,mp4a.40.2"'>
                    </video>
                </div>
                <div class="right-content">
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="songModal" tabindex="-1" role="dialog" aria-labelledby="songModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title col-1" id="songModal">Song</h5>
                    <select id="song" class="form-control col-5" v-on:change="changeSong" v-model="id">
                        <option value="new">new</option>
                        <option v-for="song in songs" v-bind:value="song.id">
                            @{{ song.title }} @{{ song.artist }}
                        </option>
                    </select>
                    <button type="button" class="close col-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <label for="title" class="form-label col-1">title</label>
                        <input type="text" id="title" class="form-control form-control-sm col-5" v-model="title">
                        <button type="button" class="btn btn-success btn-sm ml-5" v-on:click="getLyric">Get lyric <i class="far fa-file-alt"></i></button>
                    </div>
                    <div class="row">
                        <label for="artist" class="form-label col-1">artist <i class="fas fa-user"></i></label>
                        <input type="text" id="artist" class="form-control form-control-sm col-5" v-model="artist">
                    </div>
                    <div class="row">
                        <label for="playtime" class="form-label col-1">playtime</label>
                        <input type="text" id="playtime" class="form-control form-control-sm col-1" placeholder="00:00" v-model="playtime">
                    </div>
                    <div class="row">
                        <label class="form-label col-1">lyric</label>
                        <textarea id="lyric" class="form-control form-control-sm col-10" style="height:55vh" v-model="lyric"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary btn-lg" v-on:click="storeSong">save <i class="far fa-save"></i></button>
                </div>
                </div>
            </div>
            </div>
        </div>
    </body>
    <script>
        new Vue({
            el: '#app',
            data: {
                src: null,
                song: null,
                id: 'new',
                title: null,
                artist: null,
                playtime: null,
                lyric: null,
                message: null,
                songs: null,
            },
            methods: {
                handleFileSelect(event) {
                    this.src = null;
                    const file = event.target.files[0];
                    if (file.type.match('video/*')) {
                        const fileReader = new FileReader();
                        fileReader.onload = (evt) => {
                            this.src = evt.target.result;
                        }
                        fileReader.readAsDataURL(file);
                    }
                },
                storeSong: async function() {
                    const song = {
                        'id': this.id,
                        'title': this.title,
                        'artist': this.artist,
                        'playtime': this.playtime,
                        'lyric': this.lyric,
                    };
                    const response = await axios.post('/song/store', song);
                    this.message = response.data.message;
                    console.log(this.message);
                    this.getSongList();
                },
                getSongList: async function() {
                    const response = await axios.get('/song/get-list');
                    this.songs = response.data;
                },
                changeSong: async function(event) {
                    if (event.target.value != 'new') {
                        const song = this.songs.find((song) => song.id == event.target.value);
                        this.title = song.title;
                        this.artist = song.artist;
                        this.playtime = song.playtime;
                        this.lyric = song.lyric;
                    } else {
                        this.title = '';
                        this.artist = '';
                        this.playtime = '';
                        this.lyric = '';
                    }
                },
                getLyric: async function() {
                    const song = {
                        'title': this.title,
                        'artist': this.artist,
                    }
                    const response = await axios.post('/song/get-lyric', song);
                    const result = JSON.parse(JSON.stringify(response.data));
                    console.log(result.output.lyric);
                    this.lyric = result.output.lyric;
                },
            }
        })
    </script>
</html>
