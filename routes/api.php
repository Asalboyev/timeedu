<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('locale')->group(function () {

    Route::get('/news', [\App\Http\Controllers\Api\NewsController::class, 'get_posts']);
    Route::get('/news/{slug}', [\App\Http\Controllers\Api\NewsController::class, 'show_post']);

    Route::get('/video_news', [\App\Http\Controllers\Api\NewsController::class, 'get_video_post']);
    Route::get('/video_news/{slug}', [\App\Http\Controllers\Api\NewsController::class, 'show_video_post']);

    Route::get('/categories', [\App\Http\Controllers\Api\NewsController::class, 'get_categories']);
    Route::get('/categories/{slug}', [\App\Http\Controllers\Api\NewsController::class, 'show_categories']);

    Route::get('/students', [\App\Http\Controllers\Api\ApiController::class, 'get_students']);
    Route::get('/students/{id}', [\App\Http\Controllers\Api\ApiController::class, 'show_students']);

    Route::get('/menu', [\App\Http\Controllers\Api\MenuMontroller::class, 'get_menu']);
    Route::get('/menu/{id}', [\App\Http\Controllers\Api\MenuMontroller::class, 'show_menu']);

    Route::get('/faq', [ApiController::class, 'get_faq']);
    Route::get('/faq/{id}', [ApiController::class, 'show_faq']);

    Route::get('/partners', [ApiController::class, 'get_partners']);
    Route::get('/partners/{id}', [ApiController::class, 'show_partners']);

    Route::get('/banners', [ApiController::class, 'get_banner']);

    Route::get('/vacancies', [ApiController::class, 'get_vacancies']);
    Route::get('/vacancies/{id}', [ApiController::class, 'show_vacancies']);
    Route::get('siteinfo', [ApiController::class, 'getCompany']);

    Route::get('/categories', [\App\Http\Controllers\Api\NewsController::class, 'get_categories']);
    Route::get('/categories/{slug}', [\App\Http\Controllers\Api\NewsController::class, 'show_categories']);
    Route::get('/categories/filter/{id}', [\App\Http\Controllers\Api\NewsController::class, 'show_categor_product']);

    Route::get('/products', [ApiController::class, 'get_products']);
    Route::get('/product/{slug}', [ApiController::class, 'show_products']);
    Route::get('/translations', [ApiController::class, 'translations']);
    Route::post('/contacts', [ApiController::class, 'store']);




});
