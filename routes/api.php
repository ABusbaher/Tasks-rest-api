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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/tasks',['uses'=>'ToDoController@index', 'as' => 'allTasks']);
Route::get('/task/{id}',['uses'=>'ToDoController@show', 'as' => 'singleTask']);
Route::post('/task/create', ['uses'=>'ToDoController@store', 'as' => 'createTask']);
Route::delete('/task/delete/{id}', ['uses'=>'ToDoController@destroy', 'as' => 'deleteTask']);
//Route::auth();
Route::post('/auth/register', ['uses'=>'AuthorizationController@register', 'as' => 'registerUser']);
Route::post('/auth/logout', ['uses'=>'AuthorizationController@logout', 'as' => 'logoutUser']);
Route::post('/auth/login', ['uses'=>'AuthorizationController@login', 'as' => 'loginUser']);