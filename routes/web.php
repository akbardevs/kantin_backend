<?php

use Laravel\Nova\Nova;
use App\Http\Controllers\HomePage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\QaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyMediaController;
use App\Models\Promotion;

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

Route::permanentRedirect('/admin', '/nova');

Route::get('/', function () {
    return view('welcome');
});


Route::permanentRedirect('/', '/nova');
Nova::routes();
