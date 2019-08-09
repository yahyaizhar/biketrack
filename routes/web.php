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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/public/public',function(){
    return view('welcome');
});
Route::get('/phpinfo',function(){ 
    phpinfo();
});

Route::group([
    'prefix' => 'client',
    'namespace' => 'Client'
], function(){
    Auth::routes(['register' => false]);
    Route::get('/', 'HomeController@index')->name('client.home');
    Route::get('/riders', 'RiderController@showRiders')->name('client.riders');
    Route::get('/riders/locations', 'RiderController@showAllRidersLocations')->name('client.riders.locations');
    Route::get('/rider/{rider}/location', 'RiderController@showRiderLocation')->name('client.rider.location');
    Route::get('/profile', 'ProfileController@showProfile')->name('client.profile');
    Route::get('/profile/edit', 'ProfileController@edit')->name('client.profile.edit');
    Route::put('/profile', 'ProfileController@update')->name('client.profile.update');
    Route::get('/messageToSupport', 'ProfileController@messageToSupport')->name('client.messageToSupport');
    Route::post('/messageToSupport', 'ProfileController@sendMessageToSupport')->name('client.sendMessageToSupport');
    Route::post('/update/client/riders','RiderController@update_ClientRiders')->name('ClientRiders.update');
});

Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function(){
    Route::post('/update/client/riders','RiderController@update_ClientRiders')->name('ClientRiders.admin.update');
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@login')->name('admin.login');
    Route::get('/logout', 'Auth\LoginController@logout')->name('admin.logout');
    Route::get('/rider/salary','RiderController@rider_salary')->name('Rider.salary');
    Route::get('/', 'HomeController@index')->name('admin.home');
    Route::get('/livemap', 'HomeController@livemap')->name('admin.livemap');
    Route::get('/profile', 'HomeController@profile')->name('admin.profile');
    Route::put('/profile', 'HomeController@updateProfile')->name('admin.profile.update');

    // AJAX Routes
    Route::get('/clients/data', 'AjaxController@getClients')->name('admin.clients.data');
    Route::get('/riders/data', 'AjaxController@getRiders')->name('admin.riders.data');
    Route::get('/riders/{rider}/ridesReport/data', 'AjaxController@getRidesReport')->name('admin.ridesReport.data');
    // Route::get('/rider/{rider}/messages/data', 'AjaxController@getMessages')->name('admin.rider.messages.data');
  
    Route::resource('/riders', 'RiderController', [
        'as' => 'admin'
    ]);
    Route::get('/rider/{rider}/location', 'RiderController@showRiderLocation')->name('admin.rider.location');
    Route::get('/rider/{rider}/profile', 'RiderController@showRiderProfile')->name('admin.rider.profile');

    Route::get('/rider/{rider}/ridesReport', 'RiderController@showRidesReport')->name('admin.rider.ridesReport');
    Route::delete('/ridesReportRecord/{record}', 'RiderController@deleteRidesReportRecord')->name('admin.rider.ridesReport.delete');

    Route::post('/rider/{rider}/sendMessage', 'RiderController@sendSMS')->name('admin.rider.sendSMS');
    Route::post('/rider/{rider}/updateStatus', 'RiderController@updateStatus')->name('admin.rider.updateStatus');
    // Route::get('/rider/{rider}/messages', 'RiderController@showMessages')->name('admin.rider.messages');
    Route::get('/client/rider/performance','RiderController@RiderPerformance')->name('admin.riderPerformance');
    Route::get('get/ajax/rider/performance','AjaxController@getRiderPerformance')->name('admin.ajax_performance');
    Route::resource('/clients', 'ClientController', [
        'as' => 'admin'
    ]);
    Route::resource('/bikes', 'ClientController', [
        'as' => 'admin'
    ]);
    Route::get('/client/{client}/profile', 'ClientController@showClientProfile')->name('admin.client.profile');
    Route::get('/client/{client}/riders', 'ClientController@showRiders')->name('admin.clients.riders');
    Route::get('/client/{client}/assignRider', 'ClientController@assignRiders')->name('admin.clients.assignRiders');
    Route::put('/client/{client}/assignRider', 'ClientController@updateAssignedRiders')->name('admin.clients.assignRiders');
    Route::delete('/client/{client}/removeRider/{rider}', 'ClientController@removeRiders')->name('admin.clients.removeRiders');
    Route::post('/client/{client}/updateStatus', 'ClientController@updateStatus')->name('admin.client.updateStatus');
    Route::post('/client/mutlipleDelete', 'ClientController@mutlipleDelete')->name('admin.client.mutlipleDelete');

    // Client Email Route
    Route::resource('emails', 'ClientEmailController', [
        'as' => 'admin'
    ]);
});
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function(){
    // Bike
    Route::get('/bike_login','bikeController@bike_login')->name('bike.bike_login');
    Route::post('/bike_create','bikeController@create_bike')->name('bike.bike_create');
    Route::get('/bike_getData','AjaxController@getBikes')->name('bike.bike_show');
    Route::get('/bike_view','bikeController@bike_view')->name('bike.bike_view');
    // Route::get('/bike_assigned','bikeController@bike_assigned_form')->name('bike.bike_assigned');
    Route::get('/bike/{bike}/assigned', 'ClientController@bike_assigned_show')->name('bike.bike_assigned');
    Route::get('/bike/{id}/assignRider', 'ClientController@bike_assigned_toRider')->name('bike.bike_assignRiders');
    Route::put('/bike/{rider}/assignRider', 'ClientController@updateAssignedBike')->name('bike.bike_assignRiders');
    Route::delete('/rider/{rider_id}/removeBike/{bike_id}', 'ClientController@removeBikes')->name('admin.removeBikes');
    Route::post('/bike/{bike}/updateStatus', 'ClientController@updateStatusbike')->name('bike.updateStatusbike');
    Route::delete('/bike/{bike_id}', 'ClientController@mutlipleDeleteBike')->name('bike.mutlipleDeleteBike');
    Route::get('/bike/Edit/{id}','bikeController@bike_edit')->name('Bike.edit_bike');
    Route::post('/bike/update/{id}','bikeController@bike_update')->name('Bike.bike_update');
    // Route::get('/bike/assigned/show/','ClientController@bike_is_show')->name('bike.bike_show');
    Route::get('/riders/{rider}/history','ClientController@Bike_assigned_to_riders_history')->name('Bike.assignedToRiders_History');
    Route::get('/bike/{bike_id}/history','ClientController@rider_history')->name('bike.rider_history');
    Route::get('/bike/{bike_id}/profile/{rider_id}','ClientController@bike_profile')->name('bike.bike_profile');
    // end Bike
});

Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function(){
    // accounts
    Route::resource('/account', 'AccountsController', [
        'as' => 'admin'
    ]);
    // add_new_salary
    Route::get('/Add/Salary','AccountsController@add_new_salary_create')->name('account.new_salary');
    Route::post('/Salary/Added','AccountsController@new_salary_added')->name('account.added_salary');
    // end_add_new_salary

    //Mobiles
    Route::get('/mobile/create','MobileController@create_mobile_GET')->name('mobile.create_mobile_GET');
    Route::post('/mobile/create','MobileController@create_mobile_POST')->name('mobile.create_mobile_POST');
    Route::get('/mobiles','MobileController@mobiles')->name('mobile.show');
    Route::get('/mobile/{mobile}/edit','MobileController@update_mobile_GET')->name('mobile.edit');
    Route::get('/mobile/data','AjaxController@getMobiles')->name('mobile.getMobiles');
    Route::delete('/mobile/delete/{mobile_id}','MobileController@delete_mobile')->name('Mobile.delete_mobile');
    Route::post('/mobile/{id}/updateStatus','MobileController@updateStatusMobile')->name('Mobile.updatetatus');
    Route::post('/mobile/{id}/update','MobileController@update_mobile')->name('Mobile.update');
    //end Mobiles
    // mobile installement
    Route::get('/mobile/installment/create','MobileController@create_mobileInstallment')->name('MobileInstallment.create');
    Route::post('/mobile/installment/insert','MobileController@store_mobileInstallment')->name('MobileInstallment.store');
    Route::get('/mobile/installment/data','AjaxController@getMobileInstallment')->name('MobileInstallment.getinstallments');
    Route::get('/mobile/installment/show','MobileController@show_mobileInstallmenet')->name('MobileInstallment.show');
    Route::get('/mobile/installment/{mobile}/edit','MobileController@edit_mobileInstallment')->name('MobileInstallment.edit');
    Route::post('/mobile/installment/{id}/update','MobileController@update_mobileInstallment')->name('MobileInstallment.update');
    Route::delete('/mobile/installment/delete/{mobile_id}','MobileController@delete_mobileInstallment')->name('MobileInstallment.delete');
    // end mobile installement
    Route::get('/a/{id}','AjaxController@rider_name');
    
    Route::delete('/month/{month_id}', 'AccountsController@DeleteMonth')->name('account.DeleteMonth');
    Route::get('/Month/Salary','AccountsController@salary_by_month_create')->name('account.month_salary');
    Route::post('/month/{month_id}/updateStatus', 'AccountsController@updateStatusmonth')->name('account.updateStatusmonth');
    Route::get('/month/Edit/{month_id}','AccountsController@month_edit')->name('account.edit_month');
    Route::post('/month/update/{month_id}','AccountsController@month_update')->name('account.month_update');
    
//    developer salary
    Route::get('/Developer/Salary/ajax','AjaxController@getSalary_by_developer')->name('account.developer_salary_ajax');
    Route::get('/Developer/Salary','AccountsController@salary_by_developer_create')->name('account.developer_salary');
    Route::get('/Rider/To/Month/ajax/{rider_id}','AjaxController@getRiderToMonth')->name('account.RiderToMonth_ajax');
    Route::get('/Month/To/Rider/ajax/{month_id}','AjaxController@getMonthToRider')->name('account.MonthToRider_ajax');
    Route::delete('/developer/{developer_id}', 'AccountsController@DeleteDeveloper')->name('account.DeleteDeveloper');
    Route::post('/developer/{developer_id}/updateStatus', 'AccountsController@updateStatusdeveloper')->name('account.updateStatusdeveloper');
    Route::get('/developer/Edit/{developer_id}','AccountsController@developer_edit')->name('account.edit_developer');
    Route::post('/developer/update/{developer_id}','AccountsController@developer_update')->name('account.developer_update');
    
    // end developer salary
    //  map Routes
    Route::get('/rider/assign-area', 'HomeController@assign_area')->name('admin.assignArea');
    Route::post('/rider/assign-area/assign', 'HomeController@assign_area_POST')->name('admin.post.assignArea');
    Route::get('/assign/area/to/rider/{id}','HomeController@assign_area_to_rider')->name('admin.area_assign_to_rider');
    // end map Routes
// Route::get('/Rider/detail/new','RiderController@testing');
//   rider further details
Route::get('/rider/detail','RiderController@rider_details')->name('admin.Rider_details');
Route::get('/riders/details/data', 'AjaxController@getRidersDetails')->name('admin.riders_detail.data');
    Route::get('/destroyer/{id}','RiderController@destroyer');
// end rider further details
    

    // end accounts  
});
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function(){
    // accounts
    Route::resource('/NewComer', 'NewComerController', [
        'as' => 'admin'
    ]);


    Route::get('/newComer/add','NewComerController@new_comer_form')->name('NewComer.form');
    Route::post('/newComer/insert','NewComerController@insert_newcomer')->name('NewComer.insert');
    Route::get('/newComer/view','NewComerController@new_comer_view')->name('NewComer.view');
    Route::get('/newComer/view/ajax', 'AjaxController@getNewComer')->name('NewComer.view_ajax');
    Route::delete('/newComer/delete/{newComer_id}','NewComerController@delete_new_comer')->name('NewCome.delete');
    Route::get('/newComer/Edit/{id}','NewComerController@newComer_edit')->name('NewComer.edit');
    Route::post('/newComer/{id}/update', 'NewComerController@updateNewComer')->name('NewComer.updatenewComer');
   Route::get('/newComer/popup/{newComer_id}','NewComerController@newComer_popup')->name('NewComer.popup');
  Route::get('/map/testing/{id}','AjaxController@client_name');
});


Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function(){
    // accounts
    Route::resource('/Sim', 'SimController', [
        'as' => 'admin'
    ]);

 //    Start Sim Section    
Route::get('/create/Sim','SimController@add_sim')->name('Sim.new_sim');
Route::post('/store/Sim','SimController@store_sim')->name('Sim.store_sim');
Route::get('/view/records/Sim','SimController@view_records_sim')->name('Sim.view_records');
Route::get('get/ajax/Sim','AjaxController@getSims')->name('Sim.ajax_sim');
Route::get('/edit/{id}/Sim','SimController@edit_sim')->name('Sim.edit_sim');
Route::post('/update/{id}/Sim','SimController@update_sim')->name('Sim.update_sim');
Route::post('/sim/{sim_id}/updateStatus','SimController@updateStatusSim')->name('Sim.updateStatus_sim');
Route::delete('/sim/{sim_id}', 'SimController@DeleteSim')->name('Sim.DeleteSim');
     // End Sim Section  


// Start Sim Transaction Section
Route::get('/create/Transaction/Sim','SimController@add_simTransaction')->name('SimTransaction.create_sim');
Route::post('/store/Transaction/Sim','SimController@store_simTransaction')->name('SimTransaction.store_simTransaction');
Route::get('/view/Transaction/Sim','SimController@view_sim_transaction_records')->name('SimTransaction.view_records');
Route::get('/get/ajax/Transaction/Sim','AjaxController@getSimTransaction')->name('SimTransaction.ajax_simTransaction');
Route::get('edit/Transaction/{id}/Sim','SimController@edit_simTransaction')->name('SimTransaction.edit_sim');
Route::post('sim_transaction/inline_edit','SimController@edit_inline_simTransaction')->name('SimTransaction.edit_sim_inline');
Route::post('/update/Transaction/{id}/Sim','SimController@update_simTransaction')->name('SimTransaction.update');
Route::post('/simTransaction/{id}/updateStatus','SimController@updateStatusSimTransaction')->name('SimTransaction.updateStatus');
Route::delete('/simTransaction/{sim_id}', 'SimController@DeleteSimTransaction')->name('SimTransaction.DeleteSim');
// End Sim Transaction Section


// start Sim history section
Route::get('/create/history/Sim/{rider_id}','SimController@add_simHistory')->name('SimHistory.addsim');
Route::post('/store/history/Sim/{rider_id}','SimController@store_simHistory')->name('SimHistory.store_simHistory');
Route::post('/update/History/{id}/Sim','SimController@update_simHistory')->name('SimHistory.update');
Route::get('/view/Sim/{id}','SimController@view_assigned_sim')->name('Sim.view_assigned');
Route::delete('/sim/{rider_id}/removeSim/{sim_id}', 'SimController@removeSim')->name('Sim.removeSim');
Route::get('/view/{rider_id}/simHistory','SimController@sim_History')->name('Sim.simHistory');
    
// end Sim history section

//import Zomato
Route::post('/import/zomato','RiderController@import_zomato')->name('import.zomato');
Route::delete('/delete/last/import','RiderController@delete_lastImport')->name('delete.import_data');
//ends import Zomato
});
  
