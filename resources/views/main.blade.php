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
        <div class="container-fluid d-flex p-0" id="app">
            <div class="left-content">
                <label for="input-video" class="buttons"><i class="fas fa-video"></i></label>
                <input id="input-video" class="d-none" type="file" accept="video/*" v-on:change="handleFileSelect">
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
                <div class="row">
                    <label for="title" class="form-label col-1">title</label>
                    <input type="text" id="title" class="form-control form-control-sm col-5">
                    <button type="button" class="btn btn-success btn-sm ml-5">Get lyric <i class="far fa-file-alt"></i></button>
                </div>
                <div class="row">
                    <label for="lyrics" class="form-label col-1">artist <i class="fas fa-user"></i></label>
                    <input type="text" id="lyrics" class="form-control form-control-sm col-5">
                </div>
                <div class="row">
                    <label for="playtime" class="form-label col-1">playtime</label>
                    <input type="text" id="playtime" class="form-control form-control-sm col-1" placeholder="00:00">
                </div>
                <div class="row">
                    <label class="form-label col-1">lyric</label>
                    <textarea class="form-control form-control-sm col-10" style="height:55vh"></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary btn-lg">save <i class="far fa-save"></i></button>
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
                    this.src = null;

                    const file = event.target.files[0];
                    if (file.type.match('video/*')) {
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
