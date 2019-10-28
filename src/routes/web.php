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

$nameSpace = 'Rehan\Tourify\Http\Controllers';
Route::group(['namespace' => $nameSpace], function () {
    Route::get('/productTour/create', 'TourifyController@create')
        ->name('createTour');

    Route::post('/productTour/save', 'TourifyController@store')
        ->name('storeTour');

    Route::get('productTour/list', 'TourifyController@index')
        ->name('listTour');

    Route::get('productTour/edit/{tour}', 'TourifyController@edit')
        ->name('editTour');

    Route::post('productTour/update/{tour}', 'TourifyController@update')
        ->name('updateTour');

    Route::get('productTour/delete/{tour}', 'TourifyController@destroy')
        ->name('deleteTour');

});
