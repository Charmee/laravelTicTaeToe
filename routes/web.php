<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameHistoryController;
use App\Http\Controllers\GameRoundController;
use App\Http\Controllers\TicTaeController;

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

// Route::get('/', 'WelcomeController@index')
//     ->name('welcome');

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
// Route::resource('games', 'GameController')->except(['destroy']);

// Route::resource('games', [GameController::class, 'index'])->except(['destroy']);

// Route::post('game-histories', [GameHistoryController::class, 'store'])
//     ->name('gameHistories.store');

// Route::post('game-rounds', 'GameRoundController@store')
//     ->name('gameRounds.store');

//  Route::post('game-rounds', [GameRoundController::class, 'store'])
//      ->name('gameRounds.store');


Route::get('/', [TicTaeController::class, 'index'])->name('tictactoe.index');
Route::post('/play', [TicTaeController::class, 'play'])->name('tictactoe.play');
Route::post('/tictactoe/reset', [TicTaeController::class, 'reset'])->name('tictactoe.reset');


