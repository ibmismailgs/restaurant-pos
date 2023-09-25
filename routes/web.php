<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Ingredients\UnitController;
use App\Http\Controllers\Products\RecipesController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Products\RecipiesController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Ingredients\PurchaseController;
use App\Http\Controllers\Ingredients\IngredientsController;
use App\Http\Controllers\Ingredients\ReportController;

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
//Route::get('/', function () { return view('home'); });
Route::get('/', [HomeController::class,'index']);
// Route::get('/', [LoginController::class,'showLoginForm']);

Route::get('login', [LoginController::class,'showLoginForm'])->name('login');
Route::post('login', [LoginController::class,'login']);
Route::post('register', [RegisterController::class,'register']);

Route::get('password/forget',  function () {
	return view('pages.forgot-password');
})->name('password.forget');
Route::post('password/email', [ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class,'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class,'reset'])->name('password.update');


Route::group(['middleware' => 'auth'], function(){
	// logout route
	Route::get('/logout', [LoginController::class,'logout']);
	Route::get('/clear-cache', [HomeController::class,'clearCache']);

	// dashboard route
	Route::get('/dashboard', [HomeController::class,'dashboard'])->name('dashboard');

	//only those have manage_user permission will get access
	Route::group(['middleware' => 'can:manage_user'], function(){
	Route::get('/users', [UserController::class,'index']);
	Route::get('/user/get-list', [UserController::class,'getUserList']);
		Route::get('/user/create', [UserController::class,'create']);
		Route::post('/user/create', [UserController::class,'store'])->name('create-user');
		Route::get('/user/{id}', [UserController::class,'edit']);
		Route::post('/user/update', [UserController::class,'update']);
		Route::get('/user/delete/{id}', [UserController::class,'delete']);
	});

	//only those have manage_role permission will get access
	Route::group(['middleware' => 'can:manage_role|manage_user'], function(){
		Route::get('/roles', [RolesController::class,'index']);
		Route::get('/role/get-list', [RolesController::class,'getRoleList']);
		Route::post('/role/create', [RolesController::class,'create']);
		Route::get('/role/edit/{id}', [RolesController::class,'edit']);
		Route::post('/role/update', [RolesController::class,'update']);
		Route::get('/role/delete/{id}', [RolesController::class,'delete']);
	});


	//only those have manage_permission permission will get access
	Route::group(['middleware' => 'can:manage_permission|manage_user'], function(){
		Route::get('/permission', [PermissionController::class,'index']);
		Route::get('/permission/get-list', [PermissionController::class,'getPermissionList']);
		Route::post('/permission/create', [PermissionController::class,'create']);
		Route::get('/permission/update', [PermissionController::class,'update']);
		Route::get('/permission/delete/{id}', [PermissionController::class,'delete']);
	});

	// get permissions
	Route::get('get-role-permissions-badge', [PermissionController::class,'getPermissionBadgeByRole']);

    // API Documentation
    Route::get('/rest-api', function () { return view('api'); });

    Route::resource('ingredients', IngredientsController::class);
    Route::resource('product', ProductsController::class);
    Route::resource('recipes', RecipesController::class);
    Route::get('get-ingredient', [RecipesController::class, 'Ingredient'])->name('get-ingredient');
    Route::resource('purchase', PurchaseController::class);
    Route::resource('sales', SalesController::class);
    Route::get('get-products', [SalesController::class, 'Product'])->name('get-products');
    Route::get('sales-invoice/{id}', [SalesController::class, 'Invoice'])->name('sales-invoice');
    Route::get('sales-kitchen-order/{id}', [SalesController::class, 'KitchenInvoice'])->name('sales-kitchen-order');
    //general settings
    Route::get('general-settings', [HomeController::class, 'GeneralSettings'])->name('general-settings');
    Route::post('general-settings-store', [HomeController::class, 'GeneralSettingStore'])->name('general-settings-store');

    Route::resource('units', UnitController::class);
    Route::get('inventory-report', [ReportController::class, 'InventoryReport'])->name('inventory-report');

    Route::get('monthly-inventory-report', [ReportController::class, 'MonthlyInventoryReport'])->name('monthly-inventory-report');

    Route::get('purchase-report', [ReportController::class, 'PurchaseReport'])->name('purchase-report');

    Route::get('monthly-purchase-report', [ReportController::class, 'MonthlyPurchaseReport'])->name('monthly-purchase-report');

    Route::get('sale-report', [ReportController::class, 'SaleReport'])->name('sale-report');

    Route::get('monthly-sale-report', [ReportController::class, 'MonthlySaleReport'])->name('monthly-sale-report');

});


Route::get('/register', function () { return view('pages.register'); });
Route::get('/login-1', function () { return view('pages.login'); });
