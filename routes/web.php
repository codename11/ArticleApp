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
//Route::get('/articles/ajaxIndex', "ArticlesController@ajaxIndex");
Route::resource("/articles", "ArticlesController");

//Begin: Rute za Ajax listing.
Route::get('/list', 'PagesController@ListArticles');
/*Route::get('/list', function(){ 
    return view('articles.List Articles'); 
});*/

Route::post('/indexAjax','ArticlesController@ajaxIndex');
//End: Rute za Ajax listing.

//Begin: Rute za Ajax show.
Route::get('/list/{id}', 'PagesController@ShowArticle');

Route::post('/showAjax','ArticlesController@ajaxShow');
//End: Rute za Ajax show.

//Begin: Rute za Ajax delete.
//Route::get('/list/{id}', 'PagesController@DeleteArticle');

Route::post('/deleteAjax/{id}','ArticlesController@ajaxDestroy');
//End: Rute za Ajax delete.