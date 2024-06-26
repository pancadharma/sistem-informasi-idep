<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/profile', function(){
    return view('layouts.app');
});

Route::get('/home', function(){
    $name = "I Gede Adi Surya Eka Pramana Putra";
    return view('home',[$name]);
});


Route::get('/', function () {
    $title = "LOGIN IDEP SERVER";
    return view('welcome',['title'=> $title]);
});

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