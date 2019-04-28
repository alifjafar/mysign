<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('try', 'SignatureController@setSign');

Route::group(['middleware' => ['auth'], 'prefix' => 'panel'], function () {
    Route::get('dashboard', 'DashboardController')->name('dashboard');
    Route::resource('users', 'UserController');
    Route::group(['prefix' => 'user/profile'], function () {
        Route::get('/{username}', 'ProfileController@index')->name('profile');
        Route::get('/{username}/change_password', 'ProfileController@editPassword')->name('edit-password');
        Route::put('/edit/{user}', 'ProfileController@updateProfile')->name('update.profile');
        Route::put('/change_password/{user}', 'ProfileController@updatePassword')->name('update.password');
        Route::put('/{user}/update_foto', 'ProfileController@updateFoto')->name('update.foto');
    });
});
