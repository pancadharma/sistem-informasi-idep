<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DesaController;
use App\Http\Controllers\Admin\DusunController;
use App\Http\Controllers\Admin\PeranController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\SatuanController;
use App\Http\Controllers\Admin\WilayahController;
use App\Http\Controllers\Admin\CountryCountroller;
use App\Http\Controllers\Admin\MjabatanController;
use App\Http\Controllers\Admin\PartnersController;
use App\Http\Controllers\Admin\ProvinsiController;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\Admin\KabupatenController;
use App\Http\Controllers\Admin\KaitanSdgController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\MPendonorController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\JenisbantuanController;
use App\Http\Controllers\Admin\TargetReinstraController;
use App\Http\Controllers\Admin\KategoripendonorController;
use App\Http\Controllers\Admin\KelompokmarjinalController;
use App\Http\Controllers\Admin\TrProgramController;
use App\Http\Controllers\Admin\ProgramController;
use Symfony\Component\Translation\Catalogue\TargetOperation;

// Insert Usable class controller after this line to avoid conflict with others member for developent
// Need to resolve wether use ProgramController or TrProgramController











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

Route::middleware(['auth'])->group(function () {
// Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Permissions
    // Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', PermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::get('roles-permission', [RolesController::class,'getPermission'])->name('roles.permission');
    Route::get('roles-api', [RolesController::class,'getRole'])->name('roles.api');
    Route::resource('roles', RolesController::class);



    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::post('users/media', [UsersController::class, 'storeMedia'])->name('users.storeMedia');
    Route::post('users/ckmedia', [UsersController::class,'storeCKEditorImages'])->name('users.storeCKEditorImages');
    // Route::get('users-data', [UsersController::class,'getUsers'])->name('api.users');
    Route::get('users-show/{users}', [UsersController::class,'showModal'])->name('users.showmodal');
    Route::get('username-check', [UsersController::class,'checkUsername'])->name('check.username');
    Route::get('email-check', [UsersController::class,'checkEmail'])->name('check.email');
    Route::get('users-api', [UsersController::class,'api'])->name('users.api');
    Route::resource('users', UsersController::class);

    //Logs
    Route::resource('audit-logs', AuditLogsController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Country
    Route::delete('country/destroy', [CountryCountroller::class, 'massDestroy'])->name('country.massDestroy');
    Route::post('country/media', [CountryCountroller::class, 'storeMedia'])->name('country.storeMedia');
    Route::post('country/ckmedia', [CountryCountroller::class, 'storeCKEditorImages'])->name('country.storeCKEditorImages');
    Route::resource('country', CountryCountroller::class);
    Route::get('listcountry', [CountryCountroller::class, 'countrylist'])->name('country.data');

    //Provinsi
    Route::resource('provinsi', ProvinsiController::class);
    Route::get('dataprovinsi', [ProvinsiController::class, 'dataprovinsi'])->name('provinsi.data');
    Route::get('provinsi/getedit/{provinsi}', [ProvinsiController::class, 'get_edit'])->name('provinsi.getedit');

    //Kabupaten
    Route::get('datakabupaten', [KabupatenController::class, 'datakabupaten'])->name('data.kabupaten');
    Route::get('kabupaten.figma', [KabupatenController::class, 'figma'])->name('kabupaten.figma');
    Route::resource('kabupaten', KabupatenController::class);

    //Get Data in Kecamatan
    Route::get('datakecamatan', [KecamatanController::class, 'datakecamatan'])->name('data.kecamatan');
    Route::get('prov.data', [KecamatanController::class, 'provinsi'])->name('prov.data');
    Route::get('prov.data/{provinsi}', [KecamatanController::class, 'provinsi_details'])->name('prov_data');
    Route::get('kab.data/{id}', [KecamatanController::class, 'kab'])->name('kab.data');
    Route::get('kab.data/{kabupaten}', [KecamatanController::class, 'kab_details'])->name('kab_data');
    Route::get('kabupaten_data/{id}', [KecamatanController::class, 'getKabupatenByProvinsi'])->name('kabupaten_data');
    Route::resource('kecamatan', KecamatanController::class);

    //Desa
    Route::get('data-desa', [DesaController::class, 'getDesa'])->name('data.desa');
    Route::get('data-kec/{id}', [DesaController::class, 'getKecamatan'])->name('kec.data');
    Route::resource('desa', DesaController::class);

    Route::get('data-dusun', [DusunController::class, 'getDusun'])->name('data.dusun');
    Route::resource('dusun', DusunController::class);

    //Wilayah Call Drop Down / Select2
    Route::get('/api/prov', [WilayahController::class, 'getProvinsi'])->name('api.prov');
    Route::get('/api/kab/{id}', [WilayahController::class, 'getKabupaten'])->name('api.kab');
    Route::get('/api/kec/{id}', [WilayahController::class, 'getKecamatan'])->name('api.kec');
    Route::get('/api/desa/{id}', [WilayahController::class, 'getDesa'])->name('api.desa');
    Route::get('/api/dusun/{id}', [WilayahController::class, 'getDusun'])->name('api.dusun');

    //Master Jenis Bantuan
    Route::resource('jenisbantuan', JenisbantuanController::class);
    Route::get('datajenisbantuan', [JenisbantuanController::class, 'datajenisbantuan'])->name('data.jenisbantuan');

    //Master Kategori Pendonor
    Route::resource('kategoripendonor', KategoripendonorController::class);
    Route::get('datakategoripendonor', [KategoripendonorController::class, 'datakategoripendonor'])->name('data.kategoripendonor');

    //Master Jabatan
    Route::resource('mjabatan', MjabatanController::class);
    Route::get('data/mjabatan', [MjabatanController::class, 'getData'])->name('data.mjabatan');

    //Master Pendonor
    Route::resource('pendonor', MPendonorController::class);
    Route::get('datapendonor', [MPendonorController::class, 'datapendonor'])->name('data.pendonor');


    //Master Kelompok Marjinal
    Route::resource('kelompokmarjinal', KelompokmarjinalController::class);
    Route::get('datakelompokmarjinal', [KelompokmarjinalController::class, 'datakelompokmarjinal'])->name('data.kelompokmarjinal');

    //Master Partners
    Route::resource('partner', PartnersController::class);
    Route::get('partners-api', [PartnersController::class, 'getPartners'])->name('partners.api');


    //Master Target Reinstra
    Route::resource('target-reinstra', TargetReinstraController::class);
    Route::get('target-reinstra-api', [TargetReinstraController::class, 'getTargetReinstra'])->name('reinstra.api');

    //Master Satuan
    Route::resource('satuan', SatuanController::class);
    Route::get('satuan-api', [SatuanController::class, 'getSatuan'])->name('satuan.api');

    //Master Peran
    Route::resource('peran', PeranController::class);
    Route::get('data/peran', [PeranController::class, 'getData'])->name('data.peran');

    //Master Kaitan SDG
    Route::resource('kaitan_sdg', KaitanSdgController::class);
    Route::get('data/kaitan_sdg', [KaitanSdgController::class, 'getData'])->name('data.kaitan_sdg');

    // Transaction Program

    //Route::resource('program', TrProgramController::class);
    // get data for select 2 form
    Route::get('program-reinstra', [TrProgramController::class, 'TargetReinstra'])->name('program.api.reinstra');
    Route::get('program-marjinal', [TrProgramController::class, 'KelompokMarjinal'])->name('program.api.marjinal');
    Route::get('program-sdg', [TrProgramController::class, 'KaitanSDG'])->name('program.api.sdg');
    Route::post('program/media', [TrProgramController::class, 'filePendukung'])->name('program.storeMedia');

    //Route Program by Siva
    //Program
    Route::resource('program', ProgramController::class);
    Route::get('data/program', [ProgramController::class, 'getData'])->name('data.program');

    Route::delete('program/media/{media}', [ProgramController::class, 'ProgramMediaDestroy'])->name('program.media.destroy');

});
