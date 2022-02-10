<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GamesController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/all_games', [GamesController::class, 'index']);
Route::post('/create_game', [GamesController::class, 'create']);
Route::post('/game/{id}/join', [GamesController::class, 'join']);
Route::post('/game/{id}/player_action', [GamesController::class, 'player_action']);
Route::get('/games_history', [GamesController::class, 'history']);

Route::get('/{path?}', function () {
    return view('app');
});

Auth::routes();
