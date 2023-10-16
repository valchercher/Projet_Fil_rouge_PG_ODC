<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ExcelImportController;

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
Route::post('/import', [ExcelImportController::class, 'import']);

Route::controller(UserController::class)->prefix('ODC_FIL_ROUGE')->group(function(){
    Route::post('create/user','store');
    Route::get('users/export','export');
    Route::post('user/login','login');
    Route::post('user/inscription','import');
});
Route::controller(CoursController::class)->prefix('ODC_FIL_ROUGE')->group(function(){
    Route::post('create/cours','store');
    Route::get('index/{index?}','index');
    Route::get('cours/{role}/{index}/{cle?}/{libelle?}','load');
});
Route::controller(SessionController::class)->prefix('ODC_FIL_ROUGE')->group(function(){
    Route::post('create/session','store');
    Route::get('session','index');
    Route::get('session/{role}/{cle?}/{index?}','load');
    Route::post('demandeAnnuler/session/{id}','demandeAnnulerSession');
    Route::get('annulerSession/session/{id}','AnnulerSession');
    Route::get('validerSession/session/{id}','validerSession');
    Route::get('demandeAnulation/session','getDemandeAnnulation');
    Route::get('NePasAnnulerSeesion/session/{id}','NePasAnnulerSeesion');
});
Route::controller(PresenceController::class)->prefix('ODC_FIL_ROUGE')->group(function(){
    Route::post('etudiant/present','store');
});

Route::middleware('auth:sanctum')->prefix('ODC_FIL_ROUGE')->group(function(){
    Route::get('/user', function (Request $request) 
    {
        return $request->user();
    });
    Route::get('/user/logout',[UserController::class,'logout']);
});
