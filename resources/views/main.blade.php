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
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/main.css') }}" rel="stylesheet">
        <style>

        </style>
    </head>
    <body>
        <div class="content" id="app">
            <div class="left-content">
            </div>
            <div class="main-content">
                <label for="input-video">動画を選択</label>
                <input id="input-video" type="file" accept="video/*" v-on:change="handleFileSelect">
                <video controls v-if="src" muted>
                    <source :src="src" type='video/mp4; codecs="avc1.64001E,mp4a.40.2"'>
                </video>
            </div>
            <div class="right-content">
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
