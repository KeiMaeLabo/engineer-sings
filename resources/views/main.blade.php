<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>An engineer sings</title>
        <script src="{{ asset('js/app.js') }}"></script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">

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
                    <button type="button" class="buttons" data-toggle="modal" data-target="#songModal" v-on:click="getSongList"><i class="fas fa-edit"></i></button>
                </div>
                <div class="main-content">
                    <div id="selected_song">
                    <i class="fab fa-itunes-note mx-2"></i><span v-if="song" v-cloak>@{{ song.title }} - @{{ song.artist }}</span>
                    </div>
                    <video controls v-if="src" muted v-cloak>
                        <source :src="src" type='video/mp4; codecs="avc1.64001E,mp4a.40.2"'>
                    </video>
                    <div id="lyric_display" ref="lyric" v-if="song" v-bind:style="animationObject" v-html="lyricDom" v-cloak></div>
                </div>
                <div class="right-content">
                    <button type="button" class="buttons" v-on:click="showSelect"><i class="fas fa-align-left"></i></button>
                    <select id="select" class="form-control form-control-sm col-2" v-if="showSelectBox" v-on:change="showSong" v-model="selected_id" aria-hidden="true" v-cloak>
                        <option v-for="song in songs" v-bind:value="song.id">
                            @{{ song.title }} - @{{ song.artist }}
                        </option>
                    </select>
                    <button type="button" id="lyric_play" class="buttons" v-on:click="playLyric"><i class="fas fa-align-right"></i></button>
                </div>
            </div>
            <!-- songModal -->
            <div class="modal fade" id="songModal" tabindex="-1" role="dialog" aria-labelledby="songModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered main-modal" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title col-1" id="songModal">Song</h5>
                    <select id="song" class="form-control col-5" v-on:change="changeSong" v-model="id">
                        <option value="new">new</option>
                        <option v-for="song in songs" v-bind:value="song.id">
                            @{{ song.title }}<span> - </span>@{{ song.artist }}
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
                        <textarea id="lyric" class="form-control form-control-sm col-8" style="height:55vh" v-model="lyric"></textarea>
                        <div class="d-block col-1">
                            <button type="button" class="btn btn-light edit-buttons"><i class="fas fa-sort-alpha-down"></i></button>
                            <button type="button" class="btn btn-light edit-buttons" v-on:click="addLineSpacing"><i class="fas fa-level-down-alt"></i></button>
                            <button type="button" class="btn btn-light edit-buttons" v-on:click="removeLineSpacing"><i class="fas fa-level-up-alt"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary btn-lg" v-on:click="storeSong">save <i class="far fa-save"></i></button>
                    <button type="button" class="btn btn-danger ml-3" v-if="deleteToggle" v-show="!deleteFlag" v-on:click="switchDeleteFlag">delete <i class="fas fa-trash-alt"></i></button>
                    <button type="button" class="btn btn-danger ml-3" v-if="deleteToggle" v-show="deleteFlag" v-on:click="deleteSong">delete <i class="fas fa-trash-alt"></i></button>
                    <p v-if="deleteFlag">If you are sure to delete this song, press [delete <i class="fas fa-trash-alt"></i>] again.</p>
                    <button v-if="deleteFlag" type="button" class="btn btn-primary btn-sm" v-on:click="switchDeleteFlag">cancel</button>
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
                selected_id: null,
                showSelectBox: false,
                animationObject: null,
                lyricDom: null,
                deleteToggle: false,
                deleteFlag: false,
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
                showSelect() {
                    this.showSelectBox = !this.showSelectBox;
                },
                showSong() {
                    const song = this.songs.find((song) => song.id == this.selected_id);
                    this.song = song;
                    this.lyricDom = `<div id="lyric_dom">${song.lyric}</div>`;
                    this.showSelectBox = false;
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
                    if (response.data.id) {
                        this.id = response.data.id;
                        this.deleteToggle = true;
                    }
                },
                deleteSong: async function() {
                    this.switchDeleteFlag();
                    const song = {
                        'id': this.id
                    };
                    if (song.id != 'new') {
                        const response = await axios.post('/song/delete', song);
                        this.id = 'new';
                        this.title = '';
                        this.artist = '';
                        this.playtime = '';
                        this.lyric = '';
                        this.message = response.data.message;
                        console.log(this.message);
                        this.deleteToggle = false;
                        this.getSongList();
                    }
                },
                switchDeleteFlag() {
                    this.deleteFlag = !this.deleteFlag;
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
                        this.deleteToggle = true;
                    } else {
                        this.id = 'new';
                        this.title = '';
                        this.artist = '';
                        this.playtime = '';
                        this.lyric = '';
                        this.deleteToggle = false;
                    }
                    this.deleteFlag = false;
                },
                getLyric: async function() {
                    const song = {
                        'title': this.title,
                        'artist': this.artist,
                    };
                    const response = await axios.post('/song/get-lyric', song);
                    const result = JSON.parse(JSON.stringify(response.data));
                    console.log(result.output.lyric);
                    this.lyric = result.output.lyric;
                },
                playLyric() {
                    const seconds = parseInt(this.song.playtime.split(':')[0]) * 60 + parseInt(this.song.playtime.split(':')[1]);
                    const height = this.$refs.lyric.firstChild.offsetHeight;
                    this.animationObject = {
                        top: `${height * -1}px`,
                        animation: `slideUp ${seconds}s linear forwards`,
                    };
                },
                addLineSpacing() {
                    const text  = this.lyric.replace(/\r\n|\r/g, "\n");
                    const lines = text.split('\n');
                    let outLines = [];
                    for (let i=0; i<lines.length; i++) {
                        let line = '';
                        if (lines[i] === '') {
                            line = lines[i];
                        } else {
                            line = lines[i] + "\n";
                        }
                        outLines.push(line);
                    }
                    this.lyric = outLines.join('\n');
                },
                removeLineSpacing() {
                    const text  = this.lyric.replace(/\r\n|\r/g, "\n");
                    const lines = text.split('\n');
                    let outLines = [];
                    for (let i=0; i<lines.length; i++) {
                        let line = '';
                        if (lines[i] === '' && lines[i-1] !== '') {

                        } else {
                            outLines.push(lines[i]);
                        }
                    }
                    this.lyric = outLines.join('\n');
                }
            },
            mounted() {
                this.getSongList();
            }
        })
    </script>
</html>
