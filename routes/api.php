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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'namespace' => 'Rider'
], function(){
    Route::post('/login', 'Auth\LoginController@login')->name('api.rider.login');
    Route::post('/rider/store_location', 'RiderController@storeLocation')->name('api.rider.storeLocation');
    Route::post('/rider/store_sync_location', 'RiderController@storeSyncLocation')->name('api.rider.storeSyncLocation');
    Route::get('/rider/latestData/{rider}', 'RiderController@getLatestData')->name('api.rider.latestData');
    Route::post('/rider/changeStatus', 'RiderController@changeStatus')->name('api.rider.changeStatus');
    Route::post('/logout', 'RiderController@saveRideDetailsAndLogout')->name('api.rider.saveRideDetailsLogout');
    Route::post('/startday', 'RiderController@startday');
    Route::post('/endday', 'RiderController@saveRideDetailsAndEndday'); 
    Route::post('/get-reports', 'RiderController@get_reports'); 
    Route::post('/get-trips', 'RiderController@get_trips'); 
    Route::post('/add-trip', 'RiderController@add_trip'); 
    Route::post('/update-trip', 'RiderController@update_trip'); 
});

Route::get('/admin/liveLocations', 'Admin\AjaxController@loadLocations')->name('api.admin.live.locations');
Route::get('/admin/clients/liveLocations', 'Admin\AjaxController@loadClientLocations')->name('api.admin.clients.locations');
Route::get('/admin/rider/{rider}/location', 'Admin\AjaxController@loadSingleRiderLocation')->name('api.admin.rider.location');

Route::get('/client/{client}/riders', 'Admin\AjaxController@loadClientRidersLocations')->name('api.client.riders.locations');
