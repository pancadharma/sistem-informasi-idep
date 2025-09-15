<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
        Route::resource('permissions', 'App\Http\Controllers\Admin\PermissionsController')->except(['create', 'edit']);
    Route::resource('roles2', 'App\Http\Controllers\Admin\RoleController2')->except(['create', 'edit']);
});

Route::post('kegiatan/storeMedia', [App\Http\Controllers\Admin\KegiatanController::class, 'storeMedia'])->name('api.kegiatan.storeMedia');
Route::delete('kegiatan/deleteMedia/{media_id}', [App\Http\Controllers\Admin\KegiatanController::class, 'deleteMedia'])->name('api.kegiatan.delete_media');

Route::post('kegiatan/uploadTempFile', [App\Http\Controllers\Admin\KegiatanController::class, 'uploadTempFile'])->name('api.kegiatan.upload_temp_file');
Route::delete('kegiatan/deleteTempFile', [App\Http\Controllers\Admin\KegiatanController::class, 'deleteTempFile'])->name('api.kegiatan.delete_temp_file');

Route::get('kegiatan/{kegiatan}/hasil', [App\Http\Controllers\API\KegiatanController::class, 'getHasilKegiatan'])->name('api.kegiatan.get_hasil');

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('komodel-v4', [App\Http\Controllers\KomponenModel\DashboardKomponenModelV4Controller::class, 'index'])->name('komodel-v4.index');
    Route::get('komodel-v4/data', [App\Http\Controllers\KomponenModel\DashboardKomponenModelV4Controller::class, 'index']);

    Route::get('/kpi', [App\Http\Controllers\MealsDashboardController::class, 'getKpiData'])->name('kpi');
    Route::get('/geographic', [App\Http\Controllers\MealsDashboardController::class, 'getGeographicData'])->name('geographic');
    Route::get('/components', [App\Http\Controllers\MealsDashboardController::class, 'getComponentData'])->name('components');
    Route::post('/filter', [App\Http\Controllers\MealsDashboardController::class, 'getFilteredData'])->name('filter');
    Route::get('/export/pdf', [App\Http\Controllers\MealsDashboardController::class, 'exportPdf'])->name('export.pdf');
});
