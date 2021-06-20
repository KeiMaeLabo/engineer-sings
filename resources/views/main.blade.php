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
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/main.css') }}" rel="stylesheet">
        <style>

        </style>
    </head>
    <body>
        <div class="content" id="app">
            <div class="left-content">
                <label for="input-video" class="buttons"><i class="fas fa-video"></i></label>
                <input id="input-video" type="file" accept="video/*" v-on:change="handleFileSelect">
                <button type="button" id="lyric" class="buttons" data-toggle="modal" data-target="#lyricModal"><i class="fas fa-edit"></i></button>
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
        <div class="modal fade" id="lyricModal" tabindex="-1" role="dialog" aria-labelledby="lyricModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lyricModalTitle">Song</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
            <div class="modal-body">
                <label for="title" class="col-1">title</label><input type="text" id="title" class="col-5">
                <div class="divide">
                    <label for="lyrics" class="col-1">lyrics <i class="fas fa-user"></i></label><input type="text" id="lyrics" class="col-3">
                    <label for="song" class="col-1">song <i class="fas fa-user"></i></label><input type="text" id="song" class="col-3">
                </div>
                <label for="playtime" class="col-1">playtime</label><input type="text" id="playtime"class="col-1">
                <textarea class="col-12" style="height:40vh"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">save <i class="far fa-save"></i></button>
            </div>
            </div>
        </div>
        </div>
    </body>
    <script>
        new Vue({
            el: '#app',
            data: {
                src: null
            },
            methods: {
                handleFileSelect(event) {
                    // reset data
                    this.src = null;

                    // validate file
                    const file = event.target.files[0];
                    if (file.type.match('video/*')) {
                        // read file
                        const fileReader = new FileReader();
                        fileReader.onload = (evt) => {
                            this.src = evt.target.result;
                        }
                        fileReader.readAsDataURL(file);
                    }
                }
            }
        })
    </script>
</html>
