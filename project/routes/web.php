<?php

use App\Models\Provinsi;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\CountryCountroller;
use App\Http\Controllers\Admin\ProvinsiController;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\Admin\KabupatenController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\PermissionsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    $title = "LOGIN IDEP SERVER";
    return view('auth.login', ['title'=> $title]);
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('home')->with('status', session('status'));
    }
    return redirect()->route('home');
});
Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    // Permissions
    // Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    // Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);
    Route::resource('permissions', PermissionsController::class);
    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::post('users/media', [UsersController::class, 'storeMedia'])->name('users.storeMedia');
    Route::post('users/ckmedia', [UsersController::class,'storeCKEditorImages'])->name('users.storeCKEditorImages');
    Route::resource('users', UsersController::class);

    Route::resource('audit-logs', AuditLogsController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Countyr
    Route::delete('country/destroy', [CountryCountroller::class, 'massDestroy'])->name('country.massDestroy');
    Route::post('country/media', [CountryCountroller::class, 'storeMedia'])->name('country.storeMedia');
    Route::post('country/ckmedia', [CountryCountroller::class, 'storeCKEditorImages'])->name('country.storeCKEditorImages');
    Route::resource('country', CountryCountroller::class);
    Route::get('listcountry', [CountryCountroller::class, 'countrylist'])->name('country.data');


    Route::resource('provinsi', ProvinsiController::class);
    Route::get('dataprovinsi', [ProvinsiController::class, 'dataprovinsi'])->name('provinsi.data');
    Route::get('provinsi/getedit/{provinsi}', [ProvinsiController::class, 'get_edit'])->name('provinsi.getedit');

    Route::get('datakabupaten', [KabupatenController::class, 'datakabupaten'])->name('data.kabupaten');
    Route::get('kabupaten.figma', [KabupatenController::class, 'figma'])->name('kabupaten.figma');
    Route::resource('kabupaten', KabupatenController::class);

    Route::get('datakecamatan', [KecamatanController::class, 'datakecamatan'])->name('data.kecamatan');
    Route::get('prov.data', [KecamatanController::class, 'provinsi'])->name('prov.data');
    Route::get('prov.data/{provinsi}', [KecamatanController::class, 'provinsi_details'])->name('prov_data');
    Route::get('kab.data/{id}', [KecamatanController::class, 'kab'])->name('kab.data');
    Route::get('kab.data/{kabupaten}', [KecamatanController::class, 'kab_details'])->name('kab_data');
    
    Route::get('kabupaten_data/{id}', [KecamatanController::class, 'getKabupatenByProvinsi'])->name('kabupaten_data');
    
    Route::get('xxx', [KecamatanController::class, 'getKabupatenByProvinsi'])->name('xxx');

    // Route::get('kec.data/{kabupaten}', [KecamatanController::class, 'kec'])->name('kec.data');
    Route::resource('kecamatan', KecamatanController::class);
});


Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Route::get('/home', function() {
//     return view('home');
// })->name('home')->middleware('auth');



// Route::get('/home', function () {
//     if (session('status')) {
//         return redirect()->route('admin.home')->with('status', session('status'));
//     }

//     return redirect()->route('admin.home');
// });