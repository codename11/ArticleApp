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

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'PagesController@index');

//Route::resource("/articles", "ArticlesController");

//Route::get("/articles", "ArticlesController@index");
Route::get('/articles', "ArticlesController@index");
Route::get('/articles/ajaxIndex', "ArticlesController@ajaxIndex");
Route::post("/articles", "ArticlesController@store");
Route::post("/articles/create", "ArticlesController@create");
Route::post("/articles/{article}", "ArticlesController@update");
Route::get("/articles/{article}", "ArticlesController@show");
Route::post("/articles/{article}", "ArticlesController@destroy");
Route::get("/articles/{article}/edit", "ArticlesController@edit");
