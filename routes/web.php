<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'SongController@index');
Route::post('/song/store', 'SongController@store');
Route::get('/song/get-list', 'SongController@getSongList');
Route::post('/song/get-lyric', 'SongController@getLyric');
Route::post('/song/delete', 'SongController@delete');