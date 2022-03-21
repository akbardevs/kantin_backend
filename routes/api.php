<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Services\Base64Image;
use Intervention\Image\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CityController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\Informations_typesController;
use App\Http\Controllers\InformationsController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SurvivorController;
use App\Http\Controllers\UserController;
use App\Models\Notification;
use GuzzleHttp\Handler\StreamHandler;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("/logs", [UserController::class, 'getLog']);
Route::get("/connect", [UserController::class, 'testDB']);
Route::get("list/logs", [UserController::class, 'listStorage']);
Route::get("/clear/logs", [UserController::class, 'clearLog']);

Route::get("/testing/fun", [UserController::class, 'testingaja']);

Route::post("/testingcallback", [ProductController::class, 'testingcallback']);
Route::middleware(['basicAuth'])->group(function () {
	Route::get("/kantin/list", [KasirController::class, 'list']);

	Route::get("/product/{id}", [ProductController::class, 'index']);
	Route::Post("/product/pay", [ProductController::class, 'pay']);
});
// Route::get("/image/{filename}", [ProductController::class, 'image']);
Route::get('image/{filename}', function ($filename) {
	if (File::exists(storage_path('app/public/' . $filename))) {
		$path = storage_path('app/public/' . $filename);
		$file = File::get($path);
		$type = File::mimeType($path);

		$response = Response::make($file, 200);
		$response->header("Content-Type", $type);

		// return $response;
		return $response;
	}
});
// Route::get('/tes', function () {
// 	return 'asdasd';
// 	// return new StreamHandler(storage_path('logs/mylogs.log'));
//     // return File(storage_path('logs/laravel.log'));
// });
// Route::middleware('auth:api')->get('/user', function (Request $request) {
// 	return $request->user();
// });

// Route::middleware(['basicAuth'])->group(function () {

// 	Route::prefix("provinces")->group(function () {
// 		Route::get("/", [ProvinceController::class, 'index']);
// 		Route::get("/{id}", [ProvinceController::class, 'show']);
// 		Route::get("/{id}/cities", [ProvinceController::class, 'citiesInProvince']);
// 	});

// 	Route::prefix("cities")->group(function () {
// 		Route::get("/", [CityController::class, 'index']);
// 		Route::get("/{id}", [CityController::class, 'show']);
// 		Route::get("/{id}/districts", [CityController::class, 'districtsInCity']);
// 	});

// 	Route::prefix("districts")->group(function () {
// 		Route::get("/", [DistrictController::class, 'index']);
// 		Route::get("/{id}", [DistrictController::class, 'show']);
// 		Route::get("/{id}/villages", [DistrictController::class, 'villages']);
// 	});

// 	Route::prefix("info")->group(function () {
// 		Route::get("/", [InformationsController::class, 'index']);
// 		Route::get("/filter", [InformationsController::class, 'filter']);
// 		Route::post("/store", [InformationsController::class, 'store']);
// 	});

// 	Route::prefix("type")->group(function () {
// 		Route::get("/", [Informations_typesController::class, 'index']);
// 	});

// 	Route::prefix("education")->group(function () {
// 		Route::get("/", [EducationController::class, 'index']);
// 	});

// 	Route::prefix("education")->group(function () {
// 		Route::get("/", [EducationController::class, 'index']);
// 	});

// 	Route::prefix("survivor")->group(function () {
// 		Route::get("/", [SurvivorController::class, 'index']);
// 		Route::get("/{slug}", [SurvivorController::class, 'slug']);
// 	});
// });
