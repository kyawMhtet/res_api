<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\DishController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Panel\CartController;
use App\Http\Controllers\Api\Panel\MenuController;
use App\Http\Controllers\UserController;
use App\Models\Dish;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });





// admin auth
Route::group(['prefix' => 'auth'], function () {
    Route::post('/sign-up', [AuthController::class, 'register'])->name('user#register');
    Route::post('/login', [AuthController::class, 'login'])->name('user#login');
    Route::post('/refreshToken', [AuthController::class, 'refreshToken']);
});

// staff auth
// Route::group(['prefix' => 'staff_auth'], function() {
//     Route::
// });


Route::group(['middleware' => 'auth:sanctum', 'role:admin'], function () {
    Route::resource('/users', UserController::class);
    Route::post('/users/{userId}/roles', [UserController::class, 'createRole']);
    Route::patch('/users/{userId/roles', [UserController::class, 'updateRole']);
    Route::resource('/dishes', DishController::class);
    Route::post('/dishes/search', [DishController::class, 'search'])->name('search#dish');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('user#logout');
});


// order
Route::group(['prefix' => 'orders', 'middleware' => 'auth:sanctum', 'role:admin'], function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/change-status', [OrderController::class, 'changeStatus']);
});


// panel

Route::group(['prefix' => 'panel', 'middleware' => 'auth:sanctum', 'role:waiter'], function () {
    Route::get('/categories', [MenuController::class, 'index'])->name('categories#index');
    Route::get('/categories/menus/{id}', [MenuController::class, 'menus'])->name('categories#menus');
    Route::post('/categories/menus/{id}', [CartController::class, 'addToCart'])->name('add#cart');
    Route::post('/auth/logout', [AuthController::class, 'staffLogout'])->name('staff#logout');
    Route::get('/cartInfo', [CartController::class, 'index'])->name('cart#index');
    Route::post('/cartInfo/add-qty', [CartController::class, 'addQty']);
    Route::post('/cartInfo/sub-qty', [CartController::class, 'subQty']);
    Route::post('/cartInfo/check-out', [CartController::class, 'checkout']);
});
