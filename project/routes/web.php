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
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\WilayahController;
use App\Http\Controllers\Admin\CountryCountroller;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\MjabatanController;
use App\Http\Controllers\Admin\PartnersController;
use App\Http\Controllers\Admin\ProvinsiController;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\Admin\DashboardProvinsiController;
use App\Http\Controllers\Admin\KabupatenController;
use App\Http\Controllers\Admin\KaitanSdgController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\MPendonorController;
use App\Http\Controllers\Admin\TrProgramController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\JenisbantuanController;
use App\Http\Controllers\Admin\TargetReinstraController;
use App\Http\Controllers\Admin\KategoripendonorController;
use App\Http\Controllers\Admin\KelompokmarjinalController;
use App\Http\Controllers\Admin\MealsTargetProgressController;
use App\Http\Controllers\Admin\KomponenModelController;
use App\Http\Controllers\Admin\MealsPrePostTestController;
use App\Http\Controllers\API\BeneficiaryController;
use App\Http\Controllers\API\KomponenModelController as APIKomponenModelController;
use Monolog\Handler\RotatingFileHandler;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Symfony\Component\Translation\Catalogue\TargetOperation;

// Insert Usable class controller after this line to avoid conflict with others member for developent
// Need to resolve wether use ProgramController or TrProgramController

Route::get('/', function () {
    $title = "LOGIN IDEP SERVER";
    return view('auth.login', ['title' => $title]);
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

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::group(['prefix' => '/dashboard', 'as' => 'dashboard.'], function () {
        Route::get('/',                                     [HomeController::class, 'index'])->name('index');
        Route::get('/data',                                 [HomeController::class, 'getDashboardData'])->name('data');
        Route::get('/data/get-desa-chart-data',             [HomeController::class, 'getDesaPerProvinsiChartData'])->name('chart.desa');
        Route::get('/data/get-provinsi-koordinat/{id?}',    [HomeController::class, 'getFilteredProvinsi'])->name('api.markers');

        // NEW ROUTE FOR COMBINED DESA MAP DATA
        Route::get('/api/combined-desa-map-data/{provinsi_id?}', [HomeController::class, 'getCombinedDesaMapData'])->name('api.combined_desa_map_data');

        Route::get('/provinsi',                             [DashboardProvinsiController::class, 'getDashboardDataProvinsi'])->name('data.provinsi');
        Route::get('/data/provinsi/{id}',                   [DashboardProvinsiController::class, 'provinsiDetail'])->name('data.provinsi.detail');
        Route::get('/api/markers/provinsi/{id}',            [DashboardProvinsiController::class, 'getKegiatanMarkers'])->name('api.markers.provinsi');

        Route::get('/data/program-stats',                   [DashboardProvinsiController::class, 'getProgramStatsPerProvinsi'])->name('data.program-stats');
        Route::get('/data/desatable',                       [DashboardProvinsiController::class, 'getDesaTableData'])->name('data.desa');
        Route::get('/data/kabupaten-pie',                   [DashboardProvinsiController::class, 'getKabupatenPieData'])->name('data.kabupatenPie');

        Route::get('/data/get-data-desa/{id?}',             [DashboardProvinsiController::class, 'getFilteredDataDesa'])->name('provinsi.data.desa');
        Route::get('/data/chart/kabupaten/{id?}',           [DashboardProvinsiController::class, 'getChartByKabupaten'])->name('chart.kabupaten');
    });
});
// Permissions
// Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
Route::resource('permissions', PermissionsController::class);

// Roles 2
Route::resource('roles2', 'App\Http\Controllers\Admin\RoleController2')->parameters([
    'roles2' => 'role'
])->middleware('auth');

// Roles
Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
Route::get('roles-permission', [RolesController::class, 'getPermission'])->name('roles.permission');
Route::get('roles-api', [RolesController::class, 'getRole'])->name('roles.api');
Route::resource('roles', RolesController::class);



// Users
Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
Route::post('users/media', [UsersController::class, 'storeMedia'])->name('users.storeMedia');
Route::post('users/ckmedia', [UsersController::class, 'storeCKEditorImages'])->name('users.storeCKEditorImages');
Route::put('users/password', [UsersController::class, 'password'])->name('update.password');
Route::get('users-show/{users}', [UsersController::class, 'showModal'])->name('users.showmodal');
Route::get('username-check', [UsersController::class, 'checkUsername'])->name('check.username');
Route::get('email-check', [UsersController::class, 'checkEmail'])->name('check.email');
Route::get('users-api', [UsersController::class, 'api'])->name('users.api');
Route::resource('users', UsersController::class);

// Route to handle both username and id as the profile identifier
Route::get('profile/{identifier}', [UserProfileController::class, 'showProfile'])->name('profile.show');
Route::get('profile', function () {
    if (Auth::check()) {
        $identifier = Auth::user()->username ?? Auth::user()->id;
        return redirect()->route('profile.show', ['identifier' => $identifier])->with('status', session('status'));
    }
    return redirect()->route('home');
})->name('user.profile');
Route::resource('profiles', UserProfileController::class, ['except' => ['create', 'edit', 'store', 'destroy']]);


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
// Route::get('/api/prov', [WilayahController::class, 'getProvinsi'])->name('api.prov');
// Route::get('/api/kab/{id}', [WilayahController::class, 'getKabupaten'])->name('api.kab');
// Route::get('/api/kec/{id}', [WilayahController::class, 'getKecamatan'])->name('api.kec');
// Route::get('/api/desa/{id}', [WilayahController::class, 'getDesa'])->name('api.desa');
// Route::get('/api/dusun/{id}', [WilayahController::class, 'getDusun'])->name('api.dusun');

// kegiatan api
// Route::get('kegiatan/api/desa', [WilayahController::class, 'getKegiatanDesa'])->name('api.kegiatan.desa');
//Route::get('kegiatan/api/mitra', [WilayahController::class, 'getKegiatanMitra'])->name('api.kegiatan.mitra');

Route::get('kegiatan/api/penulis', [WilayahController::class, 'getKegiatanPenulis'])->name('api.kegiatan.penulis');
Route::get('kegiatan/api/jabatan', [WilayahController::class, 'getKegiatanJabatan'])->name('api.kegiatan.jabatan');
Route::get('kegiatan/api/load-desa/{id}', [WilayahController::class, 'loadKegiatanDesa'])->name('api.kegiatan.load-desa');
//kegiatan api - program
Route::get('kegiatan/api/satuan', [KegiatanController::class, 'getSatuan'])->name('api.kegiatan.satuan');
Route::get('kegiatan/api/program/{id}/out/activity', [KegiatanController::class, 'getActivityProgram'])->name('api.program.kegiatan');
Route::get('kegiatan/api/programs',                  [App\Http\Controllers\API\BeneficiaryController::class, 'getPrograms'])->name('api.data.program.kegiatan');
Route::get('kegiatan/api/programs',                  [App\Http\Controllers\API\BeneficiaryController::class, 'getPrograms'])->name('api.data.program.kegiatan');
Route::get('kegiatan/api/jenis_kegiatan', [KegiatanController::class, 'getJenisKegiatan'])->name('api.kegiatan.jenis_kegiatan');
Route::get('kegiatan/api/mitra', [KegiatanController::class, 'getKegiatanMitra'])->name('api.kegiatan.mitra');
Route::get('kegiatan/api/desa', [KegiatanController::class, 'getKegiatanDesa'])->name('api.kegiatan.desa');
Route::delete('kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
Route::delete('kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');

//Master Jenis Bantuan
Route::resource('jenisbantuan', JenisbantuanController::class);
Route::get('datajenisbantuan', [JenisbantuanController::class, 'datajenisbantuan'])->name('data.jenisbantuan');

//Master Kategori Pendonor
Route::resource('kategoripendonor', KategoripendonorController::class);
Route::get('datakategoripendonor', [KategoripendonorController::class, 'datakategoripendonor'])->name('data.kategoripendonor');

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

Route::get('program-test', [TrProgramController::class, 'testOutcome'])->name('trprogram.test.outcome');
Route::post('program-test-outcome', [TrProgramController::class, 'testSubmitOutcome'])->name('trprogram.outcome.submit');

Route::get('program-reinstra', [TrProgramController::class, 'TargetReinstra'])->name('program.api.reinstra');
Route::get('program-marjinal', [TrProgramController::class, 'KelompokMarjinal'])->name('program.api.marjinal');
Route::get('program-sdg', [TrProgramController::class, 'KaitanSDG'])->name('program.api.sdg');
Route::post('program/media', [TrProgramController::class, 'filePendukung'])->name('program.storeMedia');
Route::post('program/documents', [TrProgramController::class, 'uploadDoc'])->name('program.docs');
Route::get('program/doc', [TrProgramController::class, 'doc'])->name('program.test.doc');
Route::get('program/editdoc/{id}', [TrProgramController::class, 'docEdit'])->name('program.test.edit'); //program/editdoc/10
Route::get('trprogram/{id}/media', [TrProgramController::class, 'getMedia'])->name('trprogram.getMedia');
Route::put('trprogram/update/doc/{program}', [TrProgramController::class, 'updateDoc'])->name('trprogram.update.doc');
Route::delete('trprogram/media/{media}', [TrProgramController::class, 'deleteDoc'])->name('trprogram.delete.doc');

//Route Program by Siva
//Program
Route::get('data/program', [ProgramController::class, 'getData'])->name('data.program');
Route::get('program/api/pendonor/{id}/search', [ProgramController::class, 'searchPendonor'])->name('api.search.pendonor'); //for create data
Route::get('program/api/pendonor/{id}/data', [ProgramController::class, 'getPendonorDataEdit'])->name('api.pendonor.data'); // fill form for selected pendonor in edit program
Route::get('program/api/donor', [ProgramController::class, 'getProgramDonor'])->name('api.program.donor'); // get all pendonor data
Route::get('program/api/staff', [ProgramController::class, 'getProgramStaff'])->name('api.program.staff'); // can be used to get data staff for program
Route::get('program/api/partner', [ProgramController::class, 'getProgramPartner'])->name('api.program.partner'); // can be used to get data partner for program
Route::get('program/api/peran', [ProgramController::class, 'getProgramPeran'])->name('api.program.peran'); // can be used to get data peran for program
Route::get('program/api/lokasi', [WilayahController::class, 'getProgramLokasi'])->name('api.program.lokasi');
Route::get('program/{program}/details', [ProgramController::class, 'details'])->name('program.details');

Route::get('program/details/{output}/activity', [ProgramController::class, 'dataOutputActivity'])->name('api.program.output.activity'); //save Output Activity   Route::
Route::post('program/details/output-activity', [ProgramController::class, 'outputActivityStore'])->name('program.details.output.activity.store'); //save Output Activity
Route::PATCH('program/details/output-activity/update', [ProgramController::class, 'outputActivityUpdate'])->name('program.details.output.activity.update'); //Update Output & Activity

Route::get('program/details/modal', [ProgramController::class, 'detailsModal'])->name('program.details.modal');

Route::get('program/api/outcome/{outcome}', [ProgramController::class, 'apiOutcome'])->name('api.program.outcome');
Route::get('program/api/output/{outcome}', [ProgramController::class, 'apiOutput'])->name('api.program.output');
Route::get('program/api/objektif/{objektif}', [ProgramController::class, 'apiObjektif'])->name('api.program.objektif');
Route::resource('program', ProgramController::class);

Route::get('program/{id}/media', [ProgramController::class, 'getProgramFilesPendukung'])->name('program.files.pendukung');
Route::delete('program/media/{media}', [ProgramController::class, 'ProgramMediaDestroy'])->name('program.media.destroy');


// Route Untuk Kegiatan
Route::resource('kegiatan', KegiatanController::class);

//bentuk or sektor kegiatan
Route::get('kegiatan/api/sektor_kegiatan', [KegiatanController::class, 'getSektorKegiatan'])->name('api.kegiatan.sektor_kegiatan');
Route::get('kegiatan/api/fase-pelaporan/{programoutcomeoutputactivity_id}/', [KegiatanController::class, 'fetchNextFasePelaporan'])->name('kegiatan.fase-pelaporan');

Route::get('kegiatan/api/penulis', [ProgramController::class, 'getProgramStaff'])->name('api.kegiatan.penulis'); // can be used to get data staff for program
Route::get('kegiatan/api/jabatan', [ProgramController::class, 'getProgramPeran'])->name('api.kegiatan.jabatan'); // can be used to get data peran for program
Route::get('kegiatan/api/sektor',  [KegiatanController::class, 'getSektorKegiatan'])->name('api.kegiatan.sektor');

Route::group(['prefix' => 'api/kegiatan', 'as' => 'api.kegiatan.'], function () {
    Route::get('/list',      [App\Http\Controllers\API\KegiatanController::class, 'dataTable'])->name('list');
    Route::get('/provinsi',  [App\Http\Controllers\API\KegiatanController::class, 'getProvinsi'])->name('provinsi');
    Route::get('/kabupaten', [App\Http\Controllers\API\KegiatanController::class, 'getKabupaten'])->name('kabupaten');
    Route::get('/kecamatan', [App\Http\Controllers\API\KegiatanController::class, 'getKecamatan'])->name('kecamatan');
    Route::get('/kelurahan', [App\Http\Controllers\API\KegiatanController::class, 'getKelurahan'])->name('kelurahan');
    Route::get('/upload_media', [App\Http\Controllers\API\KegiatanController::class, 'upload_media'])->name('upload_media');
    Route::DELETE('/delete_media', [App\Http\Controllers\API\KegiatanController::class, 'delete_media'])->name('delete_media');
});

//
Route::get('/api/geojson/provinsi/{id}',        [App\Http\Controllers\API\KegiatanController::class, 'getProvinsiGeojson'])->name('api.geojson.provinsi');
Route::get('/api/geojson/kabupaten/{id}',       [App\Http\Controllers\API\KegiatanController::class, 'getKabupatenGeojson'])->name('api.geojson.kabupaten');


// Penerima Manfaat / Beneficiary
Route::group(['prefix' => 'beneficiary', 'as' => 'beneficiary.'], function () {
    Route::get('/',                             [App\Http\Controllers\Admin\BeneficiaryController::class, 'index'])->name('index');
    Route::POST('/',                            [App\Http\Controllers\Admin\BeneficiaryController::class, 'store'])->name('store');
    Route::get('/{program}/edit',               [App\Http\Controllers\Admin\BeneficiaryController::class, 'edit'])->name('edit');
    Route::get('/{id}/data',                    [App\Http\Controllers\Admin\BeneficiaryController::class, 'getBeneficiaryData'])->name('get.individual');
    Route::PUT('/{id}/edit',                    [App\Http\Controllers\Admin\BeneficiaryController::class, 'updateDataBeneficiary'])->name('edit.individual');
    Route::PUT('/{id}/edit',                    [App\Http\Controllers\Admin\BeneficiaryController::class, 'updateDataBeneficiary'])->name('edit.individual');
    Route::post('/add',                         [App\Http\Controllers\Admin\BeneficiaryController::class, 'storeBeneficiary'])->name('store.individual');
    Route::delete('/delete/{id}',               [App\Http\Controllers\Admin\BeneficiaryController::class, 'deleteBeneficiary'])->name('delete.individual');
    Route::PUT('/{beneficiary}/update',         [App\Http\Controllers\Admin\BeneficiaryController::class, 'update'])->name('update');
    Route::get('/{beneficiary}/show',           [App\Http\Controllers\Admin\BeneficiaryController::class, 'show'])->name('show');
    Route::get('/create',                       [App\Http\Controllers\Admin\BeneficiaryController::class, 'create'])->name('create');
    Route::get('/wilayah',                      [App\Http\Controllers\Admin\BeneficiaryController::class, 'wilayah'])->name('wilayah');
});

//penerima manfaat api router
Route::group(['prefix' => 'beneficiary/api/', 'as' => 'api.beneficiary.'], function () {
    Route::get('datatable',                     [App\Http\Controllers\API\BeneficiaryController::class, 'getPenerimaManfaat'])->name('datatable');
    Route::get('program',                       [App\Http\Controllers\API\BeneficiaryController::class, 'getPrograms'])->name('program');
    Route::get('provinsi',                      [App\Http\Controllers\API\BeneficiaryController::class, 'getProvinsi'])->name('provinsi');
    Route::get('kab/{id}',                      [App\Http\Controllers\API\BeneficiaryController::class, 'getKabupaten'])->name('kab');
    Route::get('kec/{id}',                      [App\Http\Controllers\API\BeneficiaryController::class, 'getKecamatan'])->name('kec');
    Route::get('desa/{id}',                     [App\Http\Controllers\API\BeneficiaryController::class, 'getDesa'])->name('desa');
    Route::get('dusun/{id}',                    [App\Http\Controllers\API\BeneficiaryController::class, 'getDusuns'])->name('dusun');
    Route::get('kelompok-rentan',               [App\Http\Controllers\API\BeneficiaryController::class, 'getKelompokRentan'])->name('kelompok.rentan');
    Route::get('kelompok-jenis',                [App\Http\Controllers\API\BeneficiaryController::class, 'getJenisKelompok'])->name('kelompok.jenis');
    Route::get('activity/{id}',                 [BeneficiaryController::class, 'getActivityProgram'])->name('program.activity');
    Route::POST('dusun/save',                   [BeneficiaryController::class, 'storeDusun'])->name('dusun.simpan');
});

Route::group(['prefix' => 'api/', 'as' => 'api.'], function () {
    Route::get('prov',                          [WilayahController::class, 'getProvinsi'])->name('prov');
    Route::get('kab/{id}',                      [WilayahController::class, 'getKabupaten'])->name('kab');
    Route::get('kec/{id}',                      [WilayahController::class, 'getKecamatan'])->name('kec');
    Route::get('desa/{id}',                     [WilayahController::class, 'getDesa'])->name('desa');
    Route::get('dusun/{id}',                    [WilayahController::class, 'getDusun'])->name('dusun');
    Route::GET('activity/{id}',                 [BeneficiaryController::class, 'getActivityProgram'])->name('program.activity');
    Route::get('jenis-kelompok',                [BeneficiaryController::class, 'getJenisKelompok'])->name('jenis.kelompok');
    Route::POST('dusun/save',                   [BeneficiaryController::class, 'storeDusun'])->name('dusun.simpan');

    // using api to store / create kegiatan
    Route::post('kegiatan/store',               [App\Http\Controllers\API\KegiatanController::class, 'storeApi'])->name('kegiatan.store');
    Route::GET('kegiatan/edit/{id}',            [App\Http\Controllers\API\KegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::PUT('kegiatan/update/{id}',          [App\Http\Controllers\API\KegiatanController::class, 'update'])->name('kegiatan.update');
    // Route::DELETE('kegiatan/delete/{id}',  [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');

    Route::get('kecamatan/{id}/kelurahan',      [WilayahController::class, 'getKelurahanByKecamatan'])->name('kecamatan.kelurahan');
    // route for api create master jenis kelompok

    Route::POST('jenis-kelompok/save',          [BeneficiaryController::class, 'apiStoreJenisKelompok'])->name('jenis_kelompok.simpan');
});

//SPATIE Activity logs
Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
Route::get('/logs/{id}', [ActivityLogController::class, 'show'])->name('logs.show');

// MEALS Komponen Model
Route::get('komodel/api/sektor', [KomponenModelController::class, 'getSektor'])->name('api.komodel.sektor');
Route::get('komodel/api/model', [KomponenModelController::class, 'getModel'])->name('api.komodel.model');
Route::group(['prefix' => 'komodel', 'as' => 'komodel.'], function () {
    Route::get('/', [App\Http\Controllers\Admin\KomponenModelController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Admin\KomponenModelController::class, 'store'])->name('store');
    Route::get('/create', [App\Http\Controllers\Admin\KomponenModelController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [KomponenModelController::class, 'edit'])->name('edit');
    Route::post('/add', [KomponenModelController::class, 'storeSingleLokasi'])->name('store.lokindi'); // tambah data lokasi baru ketika edit
    Route::get('/{id}/get', [KomponenModelController::class, 'getLokasiById'])->name('get.lokindi'); // ambil data lokasi berdasarkan id ketika edit
    Route::put('/{id}/update', [KomponenModelController::class, 'updateSingleLokasi'])->name('update.lokindi'); // update data lokasi berdasarkan id ketika edit
    Route::post('/{id}/update-model-sektor', [KomponenModelController::class, 'updateModelSektor'])->name('update.modelsektor'); // update data model sektor
    Route::delete('/lokasi/{id}', [KomponenModelController::class, 'deleteLokasi'])->name('lokasi.delete');
});

Route::group(['prefix' => 'komodel/api/', 'as' => 'api.komodel.'], function () {
    Route::get('datatable',         [App\Http\Controllers\API\KomponenModelController::class, 'getKomodelDatatable'])->name('datatable');
    Route::post('komponen',         [APIKomponenModelController::class, 'storeKomponen'])->name('komponen.store');
    Route::get('prov',              [APIKomponenModelController::class, 'getProv'])->name('prov');
    Route::get('kab{id}',               [APIKomponenModelController::class, 'getKabupatens'])->name('kab');
    Route::get('kec{id}',               [APIKomponenModelController::class, 'getKecamatans'])->name('kec');
    Route::get('desa{id}',              [APIKomponenModelController::class, 'getDesas'])->name('desa');
    Route::get('dusun{id}',             [APIKomponenModelController::class, 'getDusuns'])->name('dusun');
    Route::get('satuan',            [APIKomponenModelController::class, 'getSatuan'])->name('satuan');
});

// MEALS Pre Post
Route::group(['prefix' => 'prepost', 'as' => 'prepost.'], function () {
    Route::get('/', [MealsPrePostTestController::class, 'index'])->name('index');
    Route::get('/create', [MealsPrePostTestController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [MealsPrePostTestController::class, 'edit'])->name('edit');
    Route::post('/', [MealsPrePostTestController::class, 'store'])->name('store');
    Route::get('/datatable', [MealsPrePostTestController::class, 'getPrepostDatatable'])->name('datatable');
    Route::post('/add', [MealsPrePostTestController::class, 'storeAddPeserta'])->name('store.editadd'); //tambah data peserta baru ketika edit
    Route::get('/{id}/get', [MealsPrePostTestController::class, 'getPesertaById'])->name('get.barispeserta'); // ambil data peserta berdasarkan id ketika edit
    Route::put('/{id}/update', [MealsPrePostTestController::class, 'updateSinglepeserta'])->name('update.barispeserta'); // update data peserta berdasarkan id ketika edit
    Route::delete('/peserta/{id}', [MealsPrePostTestController::class, 'deletePeserta'])->name('peserta.delete'); // delete peserta
    Route::post('/{id}/update-prepost', [MealsPrePostTestController::class, 'updatePrePostHeader'])->name('update.header'); // update data pre post

});

// TARGET & PROGRESS
Route::group(['prefix' => 'target-progress', 'as' => 'target_progress.'], function () {
    Route::get('/',                        [App\Http\Controllers\Admin\MealsTargetProgressController::class, 'index'])->name('index');
    Route::get('/create',                    [App\Http\Controllers\Admin\MealsTargetProgressController::class, 'create'])->name('create');
    Route::get('/{program_id}/edit',        [App\Http\Controllers\Admin\MealsTargetProgressController::class, 'edit'])->name('edit');
    Route::POST('/store',                    [App\Http\Controllers\Admin\MealsTargetProgressController::class, 'store'])->name('store');
});
Route::group(['prefix' => 'target-progress/api/', 'as' => 'api.target_progress.'], function () {
    Route::get('target-progresses',        [App\Http\Controllers\API\MealsTargetProgressController::class, 'getTargetProgress'])->name('target_progresses');
    Route::get('programs',                    [App\Http\Controllers\API\MealsTargetProgressController::class, 'getPrograms'])->name('programs');
    Route::get('status-options',            [App\Http\Controllers\API\MealsTargetProgressController::class, 'getStatusOptions'])->name('status_options');
    Route::get('risk-options',                [App\Http\Controllers\API\MealsTargetProgressController::class, 'getRiskOptions'])->name('risk_options');
    Route::get('program/targets',            [App\Http\Controllers\API\MealsTargetProgressController::class, 'getTargets'])->name('targets');
    Route::get('program/{id}/histories',    [App\Http\Controllers\API\MealsTargetProgressController::class, 'getHistories'])->name('histories');

    Route::get('target-progresses/{target_progress_id}/show',    [App\Http\Controllers\API\MealsTargetProgressController::class, 'showTargets'])->name('show_targets');
});


Route::group(['prefix' => 'benchmark', 'as' => 'benchmark.'], function () {
    Route::get('/', [App\Http\Controllers\Admin\BenchmarkController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\BenchmarkController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [App\Http\Controllers\Admin\BenchmarkController::class, 'edit'])->name('edit');
    Route::put('/{id}/edit', [App\Http\Controllers\API\BenchmarkController::class, 'updateBenchmark'])->name('update');
});

Route::group(['prefix' => 'benchmark/api/', 'as' => 'api.benchmark.'], function () {
    Route::get('datatable', [App\Http\Controllers\API\BenchmarkController::class, 'getBenchmarkDatatable'])->name('datatable');

    Route::post('store', [App\Http\Controllers\API\BenchmarkController::class, 'storeBenchmark'])->name('store');
    Route::get('programs', [App\Http\Controllers\API\BenchmarkController::class, 'getPrograms'])->name('programs');
    Route::get('/benchmark/api/programs/activities', [App\Http\Controllers\API\BenchmarkController::class, 'getProgramActivities'])->name('programs.activities');
    Route::get('kegiatan', [App\Http\Controllers\API\BenchmarkController::class, 'getKegiatan'])->name('kegiatan');
    Route::get('jenis-kegiatan', [App\Http\Controllers\API\BenchmarkController::class, 'getJenisKegiatan'])->name('jenis-kegiatan');
    Route::get('lokasi', [App\Http\Controllers\API\BenchmarkController::class, 'getLokasi'])->name('lokasi');
    Route::get('compiler', [App\Http\Controllers\API\BenchmarkController::class, 'getCompilers'])->name('compiler');
});
