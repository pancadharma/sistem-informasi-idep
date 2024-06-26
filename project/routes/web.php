<?php

use App\Http\Controllers\HomeController;
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


Route::get('/', function () {
    $title = "LOGIN IDEP SERVER";
    return view('auth.login', ['title'=> $title]);
});


Route::get('/login', function () {
    // This is a placeholder function. You can replace it with your actual login logic.
    // For example, you might return a view for the login form.
    return view('auth.login');
})->name('login');

// Route::get('/home', function(){
//     $name = "I Gede Adi Surya Eka Pramana Putra";
//     return view('home', ['name' => $name]);
// });

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/profile', function(){
    return view('layouts.app');
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