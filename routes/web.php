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

Auth::routes();

Route::get('/', 'PagesController@index');
Route::resource("/articles", "ArticlesController");

//Begin: Rute za Ajax listing.
Route::get('/list', 'PagesController@ListArticles');
Route::post('/indexAjax','ArticlesController@ajaxIndex');
//End: Rute za Ajax listing.

//Begin: Rute za Ajax show.
Route::get('/list/{id}', 'PagesController@ShowArticle');
Route::post('/showAjax','ArticlesController@ajaxShow');
//End: Rute za Ajax show.

//Begin: Rute za Ajax delete.
Route::post('/deleteAjax/{id}','ArticlesController@ajaxDestroy');
//End: Rute za Ajax delete.

//Begin: Rute za Ajax update.
Route::post('/updateAjax/{id}','ArticlesController@ajaxUpdate');
//End: Rute za Ajax update.

//Begin: Rute za Ajax create.
Route::get('/ajaksCreate', 'PagesController@CreateArticle');
Route::post('/createAjax','ArticlesController@ajaxCreate');
//End: Rute za Ajax create.