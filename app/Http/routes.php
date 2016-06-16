<?php


use App\Program;
use App\Podcast;
use Illuminate\Support\Facades\Input;
use Goutte\Client;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return "rdio 3 API";
});


Route::get('/program', function () {
    $programs = Program::get();
    return response()->json($programs);
});

Route::get('/program/{program}', function ($program) {
    $podcasts = Program::where('route','=', $program)->first()->podcasts()
                      ->orderBy('date','desc')->paginate(30);
    return response()->json($podcasts);
});

Route::get('/program/{program}/search', function ($program) {
  $query = Input::get('q');
  $podcasts = Program::where('route','=', $program)->first()->podcasts()
                    ->where('title','like','%'.$query.'%')->orderBy('date','desc')->paginate();
  return response()->json($podcasts);
});

Route::get('/init', 'PodcastsController@init');
Route::get('/init/{program}', 'PodcastsController@initProgram');

Route::get('/update', 'PodcastsController@update');
Route::get('/update{program}', 'PodcastsController@updateProgram');
