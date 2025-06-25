<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\StateController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\GroupSectionController;
use App\Http\Controllers\Backend\ChangePasswordController;
use App\Http\Controllers\Backend\SiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WorklistController;
use App\Http\Controllers\CreateRequestController;
use App\Http\Controllers\InfoRequestController;
// use App\Http\Controllers\B1InputController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\InputRequestController;
use App\Http\Controllers\DelegateController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [HomeController::class, 'OneLogin'])->name('one_login');
//Route::get('/one_login', [AuthenticationController::class, 'OneLogin'])->name('one_login');
//Route::get('/', [HomeController::class, 'index']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');
Route::post('/', [HomeController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/info', [InfoRequestController::class, 'Index'])->name('info');
Route::get('/request_moc', [CreateRequestController::class, 'index'])->name('request_moc');
Route::get('/worklist', [WorklistController::class, 'index'])->name('worklist');
Route::get('/permission', [HomeController::class, 'index'])->name('permission');
Route::get('/organization', [GroupSectionController::class, 'Index'])->name('organization');
Route::get('/category', [CategoryController::class, 'Index'])->name('category');

Route::resource('users', UserController::class);
Route::resource('countries', CountryController::class);
Route::resource('states', StateController::class);
Route::resource('cities', CityController::class);
Route::resource('departments', DepartmentController::class);
Route::resource('categories', CategoryController::class);
Route::resource('dashboards', DashboardController::class);
Route::resource('group_sections', GroupSectionController::class);

Route::post('users/{user}/change-password', [ChangePasswordController::class, 'change_password'])->name('users.change.password');

// Category
Route::post('/category/store', [CategoryController::class, 'Store'])->name('categories.store');
Route::get('edit_category/{id}', [CategoryController::class, 'Edit']);
Route::get('delete_category/{id}', [CategoryController::class, 'Delete']);
Route::post('/category/update', [CategoryController::class, 'Update'])->name('categories.update');

// Delegate
Route::get('/delegate', [DelegateController::class, 'Index'])->name('delegate');
Route::get('delegate/searchDelegate',[DelegateController::class,'Search'] )->name('delegate.search');
Route::get('create_delegate/{selectedEmployee}', [DelegateController::class, 'GetEmp']);
Route::post('/delegate/store', [DelegateController::class, 'Store'])->name('delegate.store');
Route::get('edit_delegate/{id}', [DelegateController::class, 'Edit']);
Route::post('/emplodelegateyee/update', [DelegateController::class, 'Update'])->name('delegate.update');
Route::post('/delegate/delete', [DelegateController::class, 'Delete'])->name('delegate.delete');

//Employee
Route::get('/employee', [EmployeeController::class, 'Index'])->name('employee');
Route::get('employee/searchEmployee',[EmployeeController::class,'Search'] )->name('employee.search');
Route::get('/search_emp', [EmployeeController::class, 'SearchEmp'])->name('employee.search_emp');
Route::get('/search_role', [EmployeeController::class, 'SearchRole'])->name('employee.search_role');
Route::get('create_employee_emp/{selectedEmployee}', [EmployeeController::class, 'GetEmp']);
Route::get('create_employee_role/{selectedRoles}', [EmployeeController::class, 'GetRole']);
Route::post('/employee/store', [EmployeeController::class, 'Store'])->name('employee.store');
Route::get('edit_employee/{id}', [EmployeeController::class, 'Edit']);
Route::get('edit_employee_emp/{selectedEmployee}', [EmployeeController::class, 'GetEditEmp']);
Route::get('edit_employee_role/{selectedRoles}', [EmployeeController::class, 'GetEditRole']);
Route::post('/employee/update', [EmployeeController::class, 'Update'])->name('employee.update');
Route::post('/employee/delete', [EmployeeController::class, 'Delete'])->name('employee.delete');

// GroupSite
Route::get('edit-group_section/{id}', [GroupSectionController::class, 'Edit']);
Route::post('/group_section', [GroupSectionController::class, 'Update'])->name('group_sections.update');
Route::get('delete-group_section/{id}', [GroupSectionController::class, 'Delete']);
// Site
Route::get('/site', [SiteController::class, 'Index'])->name('site');;
Route::post('/store-site', [SiteController::class, 'StoreSite'])->name('site.store');
Route::get('edit-site/{id}', [SiteController::class, 'EditSite']);
Route::post('/update-site', [SiteController::class, 'UpdateSite'])->name('site.update');

Route::post('/upload_file', [CreateRequestController::class, 'UploadFile'])->name('UploadFile');
Route::post('/DeleteAttachFiles', [CreateRequestController::class, 'DeleteAttachFile'])->name('DeleteAttachFiles');
Route::post('/request_moc', [CreateRequestController::class, 'Store'])->name('request_moc.store');

Route::get('/switch_user', [UserController::class, 'SwitchUser'])->name('users.switch_user');
Route::post('/switch_user', [UserController::class, 'SwitchUser'])->name('switch_user');

// InputRequest
Route::get('pullbackWL/{request_code}', [InputRequestController::class, 'PullbackWL']);
Route::post('/search_emp', [InputRequestController::class, 'SearchEmp'])->name('SearchEmp');
Route::post('/select_pssr_team', [InputRequestController::class, 'SelectPSSRTeam'])->name('SelectPSSRTeam');
Route::post('/upload_file_c1', [CreateRequestController::class, 'UploadFileC1'])->name('UploadFileC1');
Route::post('/upload_file_e1', [CreateRequestController::class, 'UploadFileE1'])->name('UploadFileE1');
Route::post('/upload_file_e2', [CreateRequestController::class, 'UploadFileE2'])->name('UploadFileE2');
Route::post('/upload_file_f2', [CreateRequestController::class, 'UploadFileF2'])->name('UploadFileF2');
Route::post('/upload_file_g1', [CreateRequestController::class, 'UploadFileG1'])->name('UploadFileG1');
Route::post('/DeleteAttachFileC1', [CreateRequestController::class, 'DeleteAttachFileC1'])->name('DeleteAttachFileC1');
Route::post('/DeleteAttachFileE1', [CreateRequestController::class, 'DeleteAttachFileE1'])->name('DeleteAttachFileE1');
Route::post('/DeleteAttachFileE2', [CreateRequestController::class, 'DeleteAttachFileE2'])->name('DeleteAttachFileE2');
Route::post('/DeleteAttachFileF2', [CreateRequestController::class, 'DeleteAttachFileF2'])->name('DeleteAttachFileF2');
Route::post('/insertPSSRTeamDFT', [InputRequestController::class, 'insertPSSRTeamDFT'])->name('insertPSSRTeamDFT');

Route::get('{any}', function () {
  return view('employees.index');
})->where('any', '.*');

Route::get('/info/{info}{request_code}', [InfoRequestController::class, 'Index']);
// Route::post('/info/add', [B1InputController::class, 'Store'])->name('b1.store');
// Route::get('/mail', [MailController::class, 'index']);

Route::post('/info/pullback', [InputRequestController::class, 'Pullback'])->name('pullback');
Route::post('/info/addB1', [InputRequestController::class, 'StoreB1'])->name('b1.store');
Route::post('/input/addC1', [InputRequestController::class, 'StoreC1'])->name('c1.store');
Route::post('/input/addD1', [InputRequestController::class, 'StoreD1'])->name('d1.store');
Route::post('/input/addE1', [InputRequestController::class, 'StoreE1'])->name('e1.store');
Route::post('/input/addF1', [InputRequestController::class, 'StoreF1'])->name('f1.store');
Route::post('/input/addG1', [InputRequestController::class, 'StoreG1'])->name('g1.store');
Route::post('/input/addH1', [InputRequestController::class, 'StoreH1'])->name('h1.store');
Route::post('/input/addI1', [InputRequestController::class, 'StoreI1'])->name('i1.store');
Route::post('/input/addJ1', [InputRequestController::class, 'StoreJ1'])->name('j1.store');