<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

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
Route::get('/', function () {
    return Redirect::to(\route('action'));
})->middleware('auth.classic');;

Route::middleware(['throttle:5,1'])->group(function () {
  Route::get('/login', function () {
      return view('login');
    })->name('login');
    Route::post('/login', 'auth\Login');
});

Route::get('/logout', 'auth\Login@logout')->name('logout');


Route::get('/action', function () {
    return view('action');
})->name('action')->middleware('auth.classic');

Route::get('/preview/{unique_id}', function ($unique_id) {
    $preview= app('App\Http\Controllers\events\Manage')->getPreview($unique_id);
    return view('preview', ['unique_id'=>$unique_id, 'previews'=>$preview]);
})->name('preview')->middleware('auth.classic')->where('unique_id','[A-Za-z0-9]+');
Route::delete('/preview/{unique_id}/{article_id}', 'events\Manage@delete_article')->middleware('auth.classic')->where(['unique_id'=>'[A-Za-z0-9]+', 'article_id'=>'[0-9]+']);
Route::post('/preview', 'events\Manage@preview')->middleware('auth.classic');
Route::patch('/preview/{unique_id}/{article_id}', 'events\Manage@update_article')->middleware('auth.classic')->where(['unique_id'=>'[A-Za-z0-9]+', 'article_id'=>'[0-9]+']);
Route::put('/preview/{unique_id}/new', 'events\Manage@add_article')->middleware('auth.classic')->where(['unique_id'=>'[A-Za-z0-9]+']);
Route::post('/preview/{unique_id}/submit', 'events\Manage@submit')->middleware('auth.classic')->where(['unique_id'=>'[A-Za-z0-9]+']);

Route::get('/export/{unique_id}', function ($unique_id) {
    $preview= app('App\Http\Controllers\events\Manage')->getPreview($unique_id);
    return view('export', ['unique_id'=>$unique_id, 'previews'=>$preview]);
})->name('export')->middleware('auth.classic')->where('unique_id','[A-Za-z0-9]+');
