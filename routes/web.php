<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard','DashboardController@index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Route::group(['middleware'=>['auth', 'admin']],function(){
Route::group(['middleware'=>['auth']], function(){
    Route::resource('doctor', 'DoctorController');
//    Route::get('/patients','PatientlistController@index')->name('patient');
//    Route::get('/patients/all','PatientlistController@allTimeAppointment')->name('all.appointments');
//    Route::get('/status/update/{id}','PatientlistController@toggleStatus')->name('update.status');
//    Route::resource('department','DepartmentController');
});
