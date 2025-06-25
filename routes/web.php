<?php

use App\Http\Controllers\Api\EmployeeDataController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\HomeController;
// use App\Http\Controllers\B1InputController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerDetailController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffDetailController;
use App\Http\Controllers\ClubDetailController;

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
// Route::get('/', [HomeController::class, 'index']);

Auth::routes(); 

Route::get('/clublist', [ClubController::class, 'index'])->name('clublist');
Route::get('/player', [PlayerController::class, 'index'])->name('player');
Route::get('/players', [PlayerController::class, 'index'])->name('player.index'); 
Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');

 
Route::get('/player/detail/{id}', [PlayerDetailController::class, 'detail'])->name('player.detail');
Route::get('/Staff/detail/{id}', [StaffController::class, 'detail'])->name('StaffDetails');
Route::get('/clublist/detail/{id}', [ClubDetailController::class, 'Club_Detail'])->name('ClubxDetail');


 
 


Route::get('/team', [TeamController::class, 'index'])->name('team');
Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');
Route::post('/', [HomeController::class, 'logout'])->name('logout');

Route::resource('users', UserController::class);
Route::resource('countries', CountryController::class);
Route::resource('states', StateController::class);
Route::resource('cities', CityController::class);
Route::resource('departments', DepartmentController::class);
Route::resource('categories', CategoryController::class);
Route::resource('dashboards', DashboardController::class);
Route::resource('group_sections', GroupSectionController::class);

Route::post('users/{user}/change-password', [ChangePasswordController::class, 'change_password'])->name('users.change.password');



