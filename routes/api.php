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


    Route::get('/kampus', [\App\Http\Controllers\Api\NewsController::class, 'get_kampus']);
    Route::get('/kampus/{slug}', [\App\Http\Controllers\Api\NewsController::class, 'show_kampus']);

    Route::get('/video_news', [\App\Http\Controllers\Api\NewsController::class, 'get_video_post']);
    Route::get('/video_news/{slug}', [\App\Http\Controllers\Api\NewsController::class, 'show_video_post']);

    Route::get('/categories', [\App\Http\Controllers\Api\NewsController::class, 'get_categories']);
    Route::get('/categories/{slug}', [\App\Http\Controllers\Api\NewsController::class, 'show_categories']);

    Route::get('/students', [\App\Http\Controllers\Api\ApiController::class, 'get_students']);
    Route::get('/students/{id}', [\App\Http\Controllers\Api\ApiController::class, 'show_students']);

    Route::get('/students/filter/{filer}', [\App\Http\Controllers\Api\ApiController::class, 'show_students_filter']);

    Route::get('/menu', [\App\Http\Controllers\Api\MenuMontroller::class, 'get_menu']);
    Route::get('/menu/{id}', [\App\Http\Controllers\Api\MenuMontroller::class, 'show_menu']);

    Route::get('/faq', [ApiController::class, 'get_faq']);
    Route::get('/faq/{id}', [ApiController::class, 'show_faq']);

    Route::get('/partners', [ApiController::class, 'get_partners']);
    Route::get('/partners/{id}', [ApiController::class, 'show_partners']);

    Route::get('/educational-programs', [\App\Http\Controllers\Api\EducationalProgramsController::class, 'get_educational_programs']);
    Route::get('/educational-programs/{id}', [\App\Http\Controllers\Api\EducationalProgramsController::class, 'show_educational_program']);

    Route::post('/contacts', [ApiController::class, 'store']);



    Route::get('/partner-link', [ApiController::class, 'get_partners_link']);
    Route::get('/partner-link/{id}', [ApiController::class, 'show_partners_link']);

    Route::get('/certificates', [ApiController::class, 'get_certificates']);
    Route::get('/certificates/{id}', [ApiController::class, 'show_certificates']);

    Route::get('/documents', [ApiController::class, 'get_documents']);
    Route::get('/documents/{id}', [ApiController::class, 'show_documents']);

    Route::get('/journals', [ApiController::class, 'get_journals']);
    Route::get('/journals/{id}', [ApiController::class, 'show_journals']);



    Route::get('/leaderships', [\App\Http\Controllers\Api\LeadershipController::class, 'getRahbariyatEmployees']);
    Route::get('/leaderships/{id}', [\App\Http\Controllers\Api\LeadershipController::class, 'getEmployeeDetails']);

    Route::get('/department', [\App\Http\Controllers\Api\LeadershipController::class, 'showEmployeesByPosition']);
    Route::get('/department/{id}', [\App\Http\Controllers\Api\LeadershipController::class, 'getDepartmentEmployees']);
    Route::get('/department/user/{id}', [\App\Http\Controllers\Api\LeadershipController::class, 'getDepartmentEmployeesuser']);


    Route::get('/fakultet', [\App\Http\Controllers\Api\LeadershipController::class, 'getfakultet']);
    Route::get('/fakultet/{id}', [\App\Http\Controllers\Api\LeadershipController::class, 'showfakultet']);
    Route::get('/fakultet/user/{id}', [\App\Http\Controllers\Api\LeadershipController::class, 'showfakultetuser']);

    Route::get('/kafedralar', [\App\Http\Controllers\Api\LeadershipController::class, 'getKafedralar']);
    Route::get('/kafedralar/{id}', [\App\Http\Controllers\Api\LeadershipController::class, 'showKafedralar']);
    Route::get('/kafedralar/user/{id}', [\App\Http\Controllers\Api\LeadershipController::class, 'showkafedralaruser']);




//    Route::get('/department/{id}', [\App\Http\Controllers\Api\LeadershipController::class, 'showEmployeesByPosition']);



//    Route::get('/leaderships1/{id}', [\App\Http\Controllers\Api\LeadershipController::class, 'show_employ_meta']);
//
//
//    Route::get('/leaderships1', [\App\Http\Controllers\Api\LeadershipController::class, 'get_employ_meta']);
//    Route::get('/leaderships1/{id}', [\App\Http\Controllers\Api\LeadershipController::class, 'show_employ_meta']);

    Route::get('/banners', [ApiController::class, 'get_banner']);

    Route::get('/vacancies', [ApiController::class, 'get_vacancies']);
    Route::get('/vacancies/{id}', [ApiController::class, 'show_vacancies']);
    Route::get('siteinfo', [ApiController::class, 'getCompany']);

    Route::get('/categories', [\App\Http\Controllers\Api\NewsController::class, 'get_categories']);
    Route::get('/categories/{slug}', [\App\Http\Controllers\Api\NewsController::class, 'show_categories']);
    Route::get('/categories/filter/{id}', [\App\Http\Controllers\Api\NewsController::class, 'show_categor_product']);


    Route::get('/translations', [ApiController::class, 'translations']);
    Route::post('/contacts', [ApiController::class, 'store']);




});
