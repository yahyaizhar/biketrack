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
    Route::get('/active_riders','RiderController@getRider_active')->name('admin.riders.active');
    Route::get('get/ajax/rider/active','AjaxController@getActiveRiders')->name('admin.ajax_active_rider');
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
    // salik Bike
    Route::get('view/bike/salik/{id}','SalikController@bike_salik')->name('bike.bike_salik');
    // End Salik Bike
    // salik Rider
    Route::get('view/rider/salik/{id}','SalikController@rider_salik')->name('rider.rider_salik');
    Route::get('/get/salik/bike/ajax/{id}','AjaxController@getSalik_Bike')->name('bike.ajax_salik_bike');
    // End Salik Rider


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

Route::get('/sim/history/rider/{sim_id}','SimController@getRiderHistory')->name('Sim.rider.history');
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
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function(){
    Route::resource('/salik', 'SalikController', [
        'as' => 'admin'
    ]);
Route::get("/salik","SalikController@import_salik_data")->name("admin.salik");
Route::post('/import/salik','SalikController@import_Salik')->name('import.salik');
Route::get('get/ajax/salik/trip_details','AjaxController@getSalikTrip_Details')->name('admin.ajax_details');
Route::delete('/delete/last/import/salik','SalikController@delete_lastImportSalik')->name('delete.import_salik');
    


Route::get("/accounts/testing","HomeController@accounts_testing_v1")->name("admin.accounts.testing_v1");
Route::get('get/ajax/company/accounts','AjaxController@getCompanyAccounts')->name('admin.ajax_company_accounts');
Route::get('get/ajax/rider/accounts','AjaxController@getRiderAccounts')->name('admin.ajax_rider_accounts');

Route::get('/client/ranges/adt','RiderController@Rider_Range_ADT')->name('admin.ranges.adt');
Route::get('/range/ajax/adt/{data}','AjaxController@getRiderRangesADT')->name('ajax.adt');
}); 

Route::group([
    'prefix' => 'admin/accounts',
    'namespace' => 'Admin'
], function(){
    Route::resource('/accounts', 'AccountsController', [
        'as' => 'admin'
    ]);

    Route::get("/rider/account/{range}","AjaxNewController@getRiderAccounts")->name("admin.accounts.get_rider_account");

    Route::get("/rider/account","AccountsController@rider_account")->name("admin.accounts.rider_account");


    Route::get("/company/debits/get_salary_deduction/{rider_id}/{d1}/{d2}","AccountsController@get_salary_deduction")->name("admin.accounts.get_salary_deduction");
    
    Route::get("/id-charges","AccountsController@id_charges_index")->name("admin.accounts.id_charges_index");
    Route::post("/id-charges/add","AccountsController@id_charges_post")->name("admin.accounts.id_charges_post");

    Route::get("/id-charges/view","AccountsController@id_charges_view")->name("admin.accounts.id_charges_view");
    Route::get("/id-charges/view/data","AjaxNewController@getIdCharges")->name("admin.accounts.id_charges_view_data");

    Route::put('/id-charges/{charge}/updateStatus','AccountsController@updateStatusIdCharges')->name('admin.accounts.updateStatusIdCharges');
    Route::delete('/id-charges/{id_charges_id}', 'AccountsController@delete_id_charges')->name('admin.accounts.delete_id_charges');
    Route::get('/id-charges/edit/{charge_id}','AccountsController@id_charges_edit')->name('admin.id_charges_edit');
    Route::post('/id-charges/update/{charge_id}','AccountsController@id_charges_update')->name('admin.id_charges_update');


    Route::get("/workshop/add","AccountsController@workshop_index")->name("admin.accounts.workshop_index");
    Route::post("/workshop/add-data","AccountsController@workshop_post")->name("admin.accounts.workshop_post");

    Route::get("/workshop/view","AccountsController@workshop_view")->name("admin.accounts.workshop_view");
    Route::get("/workshop/view/data","AjaxNewController@getWorkShops")->name("admin.accounts.workshop_view_data");

    Route::put('/workshop/{workshop}/updateStatus','AccountsController@updateStatusWorkshop')->name('admin.accounts.updateStatusWorkshop');
    Route::delete('/workshop/{workshop_id}', 'AccountsController@delete_workshop')->name('admin.accounts.delete_workshop');
    Route::get('/workshop/edit/{shop_id}','AccountsController@workshop_edit')->name('admin.workshop_edit');
    Route::post('/workshop/update/{shop_id}','AccountsController@workshop_update')->name('admin.workshop_update');

    Route::get('/fuel_expense/create','AccountsController@fuel_expense_create')->name('admin.fuel_expense_create');
    Route::post('/fuel_expense/insert','AccountsController@fuel_expense_insert')->name('admin.fuel_expense_insert');
    Route::get('/fuel_expense/view','AccountsController@fuel_expense_view')->name('admin.fuel_expense_view');
    Route::get("/fuel_expense/view/data","AjaxNewController@getFuelExpense")->name("admin.accounts.ajax_fuelExpense");
    Route::delete('/fuel_expense/delete/{expense_id}','AccountsController@delete_fuel_expense')->name('admin.delete_fuel_expense');
    Route::post('/fuel_expense/{expense_id}/updatestatus','AccountsController@update_fuel_expense')->name('admin.update_fuel_expense');
    Route::get('/fuel_expense/edit/{expense_id}','AccountsController@edit_fuel_expense')->name('admin.edit_fuel_expense');
    Route::post('/fuel_expense/update/{expense_id}','AccountsController@update_edit_fuel_expense')->name('admin.update_edit_fuel_expense');


    Route::get("/maintenance/add","AccountsController@maintenance_index")->name("admin.accounts.maintenance_index");
    Route::post("/maintenance/add-data","AccountsController@maintenance_post")->name("admin.accounts.maintenance_post");
    Route::get("/maintenance/view","AccountsController@maintenance_view")->name("admin.accounts.maintenance_view");
    Route::get("/maintenance/view/data","AjaxNewController@getMaintenances")->name("admin.accounts.maintenance_view_data");
    Route::put('/maintenance/{maintenance}/updateStatus','AccountsController@updateStatusMaintenance')->name('admin.accounts.updateStatusMaintenance');
    Route::delete('/maintenance/{maintenance}', 'AccountsController@delete_maintenance')->name('admin.accounts.delete_maintenance');
    Route::get('/maintenance/edit/{shop_id}','AccountsController@maintenance_edit')->name('admin.maintenance_edit');
    Route::post('/maintenance/update/{shop_id}','AccountsController@maintenance_update')->name('admin.maintenance_update');

    //     Company_Expense
    Route::get('/CE/index','ExpenseController@CE_index')->name('admin.CE_index');
    Route::get('/CE/view','ExpenseController@CE_view')->name('admin.CE_view');
    Route::post('/CE/insert','ExpenseController@CE_store')->name('admin.CE_store');
    Route::post('/CE/{id}/update','ExpenseController@CE_update')->name('admin.CE_update');
    Route::get("/CE/view/data","AjaxNewController@getCompanyExpense")->name("admin.getCompanyExpense");
    Route::post('/CE/{id}/updatestatus','ExpenseController@CE_updatestatus')->name('admin.CE_updatestatus');
    Route::delete('/CE/delete/{id}','ExpenseController@CE_delete')->name('admin.CE_delete');
    Route::get('/CE/edit/{id}','ExpenseController@CE_edit')->name('admin.CE_edit');
// End Company_Expense

//edirham
Route::get("/edirham/add","AccountsController@edirham_index")->name("admin.accounts.edirham_index");
Route::post("/edirham/add-data","AccountsController@edirham_post")->name("admin.accounts.edirham_post");
Route::get("/edirham/view","AccountsController@edirham_view")->name("admin.accounts.edirham_view");
Route::get("/edirham/view/data","AjaxNewController@getEdirhams")->name("admin.accounts.edirham_view_data");
Route::put('/edirham/{edirham}/updateStatus','AccountsController@updateStatusEdirham')->name('admin.accounts.updateStatusEdirham');
Route::delete('/edirham/{edirham}', 'AccountsController@delete_edirham')->name('admin.accounts.delete_edirham');
Route::get('/edirham/edit/{id}','AccountsController@edirham_edit')->name('admin.edirham_edit');
Route::post('/edirham/update/{id}','AccountsController@edirham_update')->name('admin.edirham_update');

//edns edirham
//     WPS
Route::get('/wps/index','ExpenseController@wps_index')->name('admin.wps_index');
Route::get('/wps/view','ExpenseController@wps_view')->name('admin.wps_view');
Route::post('/wps/insert','ExpenseController@wps_store')->name('admin.wps_store');
Route::post('/wps/{id}/update','ExpenseController@wps_update')->name('admin.wps_update');
Route::get("/wps/view/data","AjaxNewController@getWPS")->name("admin.getWPS");
Route::post('/wps/{id}/updatestatus','ExpenseController@wps_updatestatus')->name('admin.wps_updatestatus');
Route::delete('/wps/delete/{id}','ExpenseController@wps_delete')->name('admin.wps_delete');
Route::get('/wps/edit/{id}','ExpenseController@wps_edit')->name('admin.wps_edit');
// End WPS

//     ADVANCE & RETURN
Route::get('/AR/index','ExpenseController@AR_index')->name('admin.AR_index');
Route::get('/AR/view','ExpenseController@AR_view')->name('admin.AR_view');
Route::post('/AR/insert','ExpenseController@AR_store')->name('admin.AR_store');
Route::post('/AR/{id}/update','ExpenseController@AR_update')->name('admin.AR_update');
Route::get("/AR/view/data","AjaxNewController@getAR")->name("admin.getAR");
Route::post('/AR/{id}/updatestatus','ExpenseController@AR_updatestatus')->name('admin.AR_updatestatus');
Route::delete('/AR/delete/{id}','ExpenseController@AR_delete')->name('admin.AR_delete');
Route::get('/AR/edit/{id}','ExpenseController@AR_edit')->name('admin.AR_edit');
// End ADVANCE & RETURN
});

