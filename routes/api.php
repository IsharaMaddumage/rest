<?php

use Illuminate\Http\Request;

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


Route::get('/test',function(){
    return "ok"; 
});
Route::post('/login', 'ApiController@login');
Route::post('/register', 'ApiController@register');
 
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'ApiController@logout');
 
    Route::get('user', 'ApiController@getAuthUser');
 
    Route::get('employees', 'EmployeeController@index');
    Route::get('employees/{id}', 'EmployeeController@show');
    Route::post('employees', 'EmployeeController@store');
    Route::put('employees/{id}', 'EmployeeController@update');
    Route::delete('employees/{id}', 'EmployeeController@destroy');
});
