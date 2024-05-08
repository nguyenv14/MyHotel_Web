<?php

use App\Http\Controllers\ApiAreaController;
use App\Http\Controllers\ApiBannerController;
use App\Http\Controllers\ApiCustomerController;
use App\Http\Controllers\ApiHotelController;
use App\Http\Controllers\ApiOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Đặc Điểm Của API Là Không Xử Lý Được Session -> Cách Xử Lý Là Viết Bên Route Chứ Bên Này Không Được */
/* Mặc định là api/admin/category/all-category */
/* Các Bảo Mật API Được Sử Dụng Json Web Token JWT Tìm Hiểu Thêm Để Bảo Mật API */
Route::get('admin/category/all-category', 'App\Http\Controllers\APICategoryProduct@all_category');
Route::GET('/get-brand', 'App\Http\Controllers\APITestController@getAPi');


// User 
Route::post('/check-login', [ApiCustomerController::class, 'logIn']);


//Area
Route::get('/area/get-area-list-have-hotel', [ApiAreaController::class, 'getAreaListHaveHotel']);

//Hotel
Route::get('/hotel/get-hotel-list-by-type', [ApiHotelController::class, 'getHotelList']);
Route::get('/hotel/get-hotel-list-by-area', [ApiHotelController::class, 'getHotelListByArea']);
Route::get('/hotel/get-hotel-by-id', [ApiHotelController::class, 'getHotelById']);


//Banner
Route::get('/banner/get-banner-list', [ApiBannerController::class, 'getBannerList']);

//Order 
Route::get('/order/get-order-list-by-status',  [ApiOrderController::class, 'getOrderListByCustomerId']);

Route::post('/order/cancel-order-by-customer',  [ApiOrderController::class, 'cancelOrderByCustomer']);


Route::post('/order/evaluate-customer',  [ApiOrderController::class, 'evaluateCustomer']);

