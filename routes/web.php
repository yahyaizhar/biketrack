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

// Ajax Routes
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
], function(){
    Route::get('/riders/data', 'AjaxController@getRiders')->name('admin.riders.data');
    Route::get('/riders/{rider}/ridesReport/data', 'AjaxController@getRidesReport')->name('admin.ridesReport.data');
    Route::get('get/ajax/rider/active','AjaxController@getActiveRiders')->name('admin.ajax_active_rider');
    Route::get('/riders/details/data', 'AjaxController@getRidersDetails')->name('admin.riders_detail.data');
    Route::get('/clients/data', 'AjaxController@getClients')->name('admin.clients.data');
    Route::get('get/ajax/rider/performance','AjaxController@getRiderPerformance')->name('admin.ajax_performance');
    Route::get('/range/ajax/adt/{data}','AjaxController@getRiderRangesADT')->name('ajax.adt'); 
    Route::get('/bike_getData','AjaxController@getBikes')->name('bike.bike_show');
    Route::get('/get/salik/bike/ajax/{id}','AjaxController@getSalik_Bike')->name('bike.ajax_salik_bike');
    Route::get('get/ajax/salik/trip_details','AjaxController@getSalikTrip_Details')->name('admin.ajax_details');
    Route::get('/Developer/Salary/ajax','AjaxController@getSalary_by_developer')->name('account.developer_salary_ajax');
    Route::get('/Rider/To/Month/ajax/{rider_id}','AjaxController@getRiderToMonth')->name('account.RiderToMonth_ajax');
    Route::get('/Month/To/Rider/ajax/{month_id}','AjaxController@getMonthToRider')->name('account.MonthToRider_ajax');
    Route::get('/CE/Report/ajax/{month_id}','AjaxNewController@getCE_REPORT')->name('account.getCE_REPORT');
    Route::get('/Company/Overall/Report/ajax/{month_id}','AjaxNewController@getCompany_overall_REPORT')->name('account.getCompany_overall_REPORT');
    Route::get('get/ajax/company/accounts','AjaxController@getCompanyAccounts')->name('admin.ajax_company_accounts');
    Route::get('get/ajax/rider/accounts','AjaxController@getRiderAccounts')->name('admin.ajax_rider_accounts');
    Route::get("/accounts/rider/account/{range}","AjaxNewController@getRiderAccounts")->name("admin.accounts.get_rider_account");
    Route::get("/accounts/company/account/{range}","AjaxNewController@getCompanyAccounts")->name("admin.accounts.get_company_account");
    Route::get('/newComer/view/ajax', 'AjaxController@getNewComer')->name('NewComer.view_ajax');
    Route::get('get/ajax/Sim','AjaxController@getSims')->name('Sim.ajax_sim');
    Route::get('/map/testing/{id}','AjaxController@client_name');
    Route::get('/get/ajax/Transaction/Sim/{month}','AjaxController@getSimTransaction')->name('SimTransaction.ajax_simTransaction');
    Route::get('/get/ajax/Transaction/Mobile/{month}','AjaxNewController@getMobileTransaction')->name('Transaction.getMobileTransaction');
    Route::get('/mobile/installment/data','AjaxController@getMobileInstallment')->name('MobileInstallment.getinstallments');
    Route::get('/mobile/data','AjaxController@getMobiles')->name('mobile.getMobiles');
    Route::get("/client_income/view/data","AjaxNewController@getclient_income")->name("admin.getclient_income");
    Route::get("/accounts/fuel_expense/view/data","AjaxNewController@getFuelExpense")->name("admin.accounts.ajax_fuelExpense");
    Route::get("/accounts/id-charges/view/data","AjaxNewController@getIdCharges")->name("admin.accounts.id_charges_view_data");
    Route::get("/accounts/workshop/view/data","AjaxNewController@getWorkShops")->name("admin.accounts.workshop_view_data");
    Route::get("/accounts/edirham/view/data","AjaxNewController@getEdirhams")->name("admin.accounts.edirham_view_data");
    Route::get("/accounts/maintenance/view/data","AjaxNewController@getMaintenances")->name("admin.accounts.maintenance_view_data");
    Route::get("/accounts/CE/view/data","AjaxNewController@getCompanyExpense")->name("admin.getCompanyExpense");
    Route::get("/accounts/wps/view/data","AjaxNewController@getWPS")->name("admin.getWPS");
    Route::get("/accounts/AR/view/data","AjaxNewController@getAR")->name("admin.getAR");
    Route::get('/accounts/income/zomato/ajax/data','AjaxNewController@income_zomato_ajax')->name('admin.accounts.income_zomato_ajax');

    Route::get("/accounts/company/bills/{range}","AjaxNewController@getCompanyAccountsBills")->name("admin.accounts.get_company_account_bills");
});
// End Ajax Routes

// Client Side
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
// end Client Side
// Riders
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:riders']
], function(){
    Route::resource('/riders', 'RiderController', [
        'as' => 'admin'
    ]);
    Route::post('/update/kingriders/ID','RiderController@update_kingriders_id')->name('KingRiders.admin.update');
    Route::get('/rider/{rider}/location', 'RiderController@showRiderLocation')->name('admin.rider.location');
    Route::get('/rider/{rider}/profile', 'RiderController@showRiderProfile')->name('admin.rider.profile');
    Route::get('/active_riders','RiderController@getRider_active')->name('admin.riders.active');
    Route::get('/rider/{rider}/ridesReport', 'RiderController@showRidesReport')->name('admin.rider.ridesReport');
    Route::delete('/ridesReportRecord/{record}', 'RiderController@deleteRidesReportRecord')->name('admin.rider.ridesReport.delete');
    Route::post('/rider/{rider}/sendMessage', 'RiderController@sendSMS')->name('admin.rider.sendSMS');
    Route::post('/rider/{rider}/updateStatus', 'RiderController@updateStatus')->name('admin.rider.updateStatus');  
//  map Routes
    Route::get('/rider/assign-area', 'HomeController@assign_area')->name('admin.assignArea');
    Route::post('/rider/assign-area/assign', 'HomeController@assign_area_POST')->name('admin.post.assignArea');
    Route::get('/assign/area/to/rider/{id}','HomeController@assign_area_to_rider')->name('admin.area_assign_to_rider');
// end map Routes
//   rider further details
    Route::get('/rider/detail','RiderController@rider_details')->name('admin.Rider_details');
    Route::get('/destroyer/{id}','RiderController@destroyer');
    Route::get('/livemap', 'HomeController@livemap')->name('admin.livemap');
// end rider further details
});
// end Riders
// clients
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:clients']
], function(){
    Route::post('/update/client/riders','RiderController@update_ClientRiders')->name('ClientRiders.admin.update');
    Route::get('/client/rider/performance','RiderController@RiderPerformance')->name('admin.riderPerformance');
    Route::resource('/clients', 'ClientController', [
        'as' => 'admin'
    ]);
    Route::get('/client/{client}/profile', 'ClientController@showClientProfile')->name('admin.client.profile');
    Route::get('/client/{client}/riders', 'ClientController@showRiders')->name('admin.clients.riders');
    Route::get('/client/{client}/assignRider', 'ClientController@assignRiders')->name('admin.clients.assignRiders');
    Route::put('/client/{client}/assignRider', 'ClientController@updateAssignedRiders')->name('admin.clients.assignRiders');
    Route::delete('/client/{client}/removeRider/{rider}', 'ClientController@removeRiders')->name('admin.clients.removeRiders');
    Route::post('/client/{client}/updateStatus', 'ClientController@updateStatus')->name('admin.client.updateStatus');
    Route::post('/client/mutlipleDelete', 'ClientController@mutlipleDelete')->name('admin.client.mutlipleDelete');
    Route::resource('emails', 'ClientEmailController', [
        'as' => 'admin'
    ]);
    Route::get('/client/ranges/adt','RiderController@Rider_Range_ADT')->name('admin.ranges.adt'); 
//import Zomato
    Route::post('/import/zomato','RiderController@import_zomato')->name('import.zomato');
    Route::delete('/delete/last/import','RiderController@delete_lastImport')->name('delete.import_data');
//ends import Zomato
});
// clients
// Bike
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:bikes']
], function(){
    Route::resource('/bikes', 'ClientController', [
        'as' => 'admin'
    ]);
    Route::get('/bike_login','bikeController@bike_login')->name('bike.bike_login');
    Route::post('/bike_create','bikeController@create_bike')->name('bike.bike_create');
    Route::get('/bike_view','bikeController@bike_view')->name('bike.bike_view');
    Route::get('/bike/{bike}/assigned', 'ClientController@bike_assigned_show')->name('bike.bike_assigned');
    Route::get('/bike/{id}/assignRider', 'ClientController@bike_assigned_toRider')->name('bike.bike_assignRiders');
    Route::put('/bike/{rider}/assignRider', 'ClientController@updateAssignedBike')->name('bike.bike_assignRiders');
    Route::delete('/rider/{rider_id}/removeBike/{bike_id}', 'ClientController@removeBikes')->name('admin.removeBikes');
    Route::post('/bike/{bike}/updateStatus', 'ClientController@updateStatusbike')->name('bike.updateStatusbike');
    Route::delete('/bike/{bike_id}', 'ClientController@mutlipleDeleteBike')->name('bike.mutlipleDeleteBike');
    Route::get('/bike/Edit/{id}','bikeController@bike_edit')->name('Bike.edit_bike');
    Route::post('/bike/update/{id}','bikeController@bike_update')->name('Bike.bike_update');
    Route::get('/riders/{rider}/history','ClientController@Bike_assigned_to_riders_history')->name('Bike.assignedToRiders_History');
    Route::get('/bike/{bike_id}/history','ClientController@rider_history')->name('bike.rider_history');
    Route::get('/bike/{bike_id}/profile/{rider_id}','ClientController@bike_profile')->name('bike.bike_profile');
    Route::get('view/bike/salik/{id}','SalikController@bike_salik')->name('bike.bike_salik');
    Route::get('view/rider/salik/{id}','SalikController@rider_salik')->name('rider.rider_salik');
    Route::get('/bike/rent/view','bikeController@create_bike_rent')->name('admin.create_bike_rent');
    Route::post('/insert/bike/rent','bikeController@post_bike_rent')->name('admin.post_bike_rent');
// salik 
    Route::resource('/salik', 'SalikController', [
    'as' => 'admin'
    ]);
    Route::get("/salik","SalikController@import_salik_data")->name("admin.salik");
    Route::post('/import/salik','SalikController@import_Salik')->name('import.salik');
    Route::delete('/delete/last/import/salik','SalikController@delete_lastImportSalik')->name('delete.import_salik');

    Route::get('/add/salik','SalikController@add_salik')->name("salik.add_salik");
    Route::get('/store/salik/{rider_id}','SalikController@store_salik')->name("salik.store_salik");
    Route::post('/insert/salik','SalikController@insert_salik')->name('Saik.insert_salik');

    Route::get('/salik/ajax/get_active_riders/{rider_id}/{month}','SalikController@get_active_riders_ajax_salik')->name('Saik.get_active_riders_ajax_salik');
// end salik   

});
// Bike

// Accounts
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:accounts']
], function(){
    Route::get('/Add/Salary','AccountsController@add_new_salary_create')->name('account.new_salary');
    Route::post('/Salary/Added','AccountsController@new_salary_added')->name('account.added_salary');
    
    Route::delete('/month/{month_id}', 'AccountsController@DeleteMonth')->name('account.DeleteMonth');
    Route::get('/Month/Salary','AccountsController@salary_by_month_create')->name('account.month_salary');
    Route::post('/month/{month_id}/updateStatus', 'AccountsController@updateStatusmonth')->name('account.updateStatusmonth');
    Route::get('/month/Edit/{month_id}','AccountsController@month_edit')->name('account.edit_month');
    Route::get('/month/Edit/view/{month_id}','AccountsController@month_edit_view')->name('account.edit_month_view');
    Route::post('/month/update/{month_id}','AccountsController@month_update')->name('account.month_update');
    
    Route::get('/Developer/Salary','AccountsController@salary_by_developer_create')->name('account.developer_salary');
    Route::delete('/developer/{developer_id}', 'AccountsController@DeleteDeveloper')->name('account.DeleteDeveloper');
    Route::post('/developer/{developer_id}/updateStatus', 'AccountsController@updateStatusdeveloper')->name('account.updateStatusdeveloper');
    Route::get('/developer/Edit/{developer_id}','AccountsController@developer_edit')->name('account.edit_developer');
    Route::get('/developer/Edit/view/{developer_id}','AccountsController@developer_edit_view')->name('account.edit_developer_view');
    Route::post('/developer/update/{developer_id}','AccountsController@developer_update')->name('account.developer_update');

    Route::get("/Salary/accounts/rider/account","AccountsController@rider_account")->name("admin.accounts.rider_account");
    Route::get("/Salary/accounts/company/account","AccountsController@company_account")->name("admin.accounts.company_account");
    Route::get('/rider/accounts/{id}/updateStatus','AccountsController@updatePaymentStatus')->name('Rider.updatePaymentStatus');

    Route::get("/accounts/company/debits/get_salary_deduction/{month}/{rider_id}","AccountsController@get_salary_deduction")->name("admin.accounts.get_salary_deduction");

    Route::get("/Salary/accounts/rider/expense","AccountsController@rider_expense_get")->name("admin.accounts.rider_expense_get");
    Route::post("/accounts/rider/expense/add","AccountsController@rider_expense_post")->name("admin.accounts.rider_expense_post");

    Route::post("/accounts/rider/cash/add","AccountsController@rider_cash_add")->name("admin.accounts.rider_cash_add");

    Route::post("/accounts/company/profit/add","AccountsController@add_company_profit")->name("admin.accounts.add_company_profit");
    Route::get("/company/overall/report","AccountsController@company_overall_report")->name("admin.accounts.company_overall_report");
    Route::put('/bill/payment/{id}/updateStatus','AccountsController@updateBillPaymentStatus')->name('admin.updateBillPaymentStatus');
});
// end Accounts

// Expense
Route::group([
    'prefix' => 'admin/kr-bikes', 
    'namespace' => 'Admin',
    'middleware' => ['roles:kr_bikes']
], function(){
    Route::get("/kr-account","KRController@account_view")->name("admin.KR_Bikes.account_view");
});

// Expense
Route::group([
    'prefix' => 'admin/accounts', 
    'namespace' => 'Admin',
    'middleware' => ['roles:expense']
], function(){
// fuel_expense
    Route::get('/fuel_expense/create','AccountsController@fuel_expense_create')->name('admin.fuel_expense_create');
    Route::post('/fuel_expense/insert','AccountsController@fuel_expense_insert')->name('admin.fuel_expense_insert');
    Route::get('/fuel_expense/view','AccountsController@fuel_expense_view')->name('admin.fuel_expense_view');
    
    Route::delete('/fuel_expense/delete/{expense_id}','AccountsController@delete_fuel_expense')->name('admin.delete_fuel_expense');
    Route::post('/fuel_expense/{expense_id}/updatestatus','AccountsController@update_fuel_expense')->name('admin.update_fuel_expense');
    Route::get('/fuel_expense/edit/{expense_id}','AccountsController@edit_fuel_expense')->name('admin.edit_fuel_expense');
    Route::post('/fuel_expense/update/{expense_id}','AccountsController@update_edit_fuel_expense')->name('admin.update_edit_fuel_expense');
    Route::get('/fuel_expense/edit/view/{expense_id}','AccountsController@edit_fuel_expense_view')->name('admin.edit_fuel_expense_view');
// end fuel_expense
// id-charges
    Route::get("/id-charges","AccountsController@id_charges_index")->name("admin.accounts.id_charges_index");
    Route::post("/id-charges/add","AccountsController@id_charges_post")->name("admin.accounts.id_charges_post");
    Route::get("/id-charges/view","AccountsController@id_charges_view")->name("admin.accounts.id_charges_view");
    Route::put('/id-charges/{charge}/updateStatus','AccountsController@updateStatusIdCharges')->name('admin.accounts.updateStatusIdCharges');
    Route::delete('/id-charges/{id_charges_id}', 'AccountsController@delete_id_charges')->name('admin.accounts.delete_id_charges');
    Route::get('/id-charges/edit/{charge_id}','AccountsController@id_charges_edit')->name('admin.id_charges_edit');
    Route::post('/id-charges/update/{charge_id}','AccountsController@id_charges_update')->name('admin.id_charges_update');
    Route::get('/id-charges/edit/view/{charge_id}','AccountsController@id_charges_edit_view')->name('admin.id_charges_edit_view');
// end id-charges
// workshop
    Route::get("/workshop/add","AccountsController@workshop_index")->name("admin.accounts.workshop_index");
    Route::post("/workshop/add-data","AccountsController@workshop_post")->name("admin.accounts.workshop_post");
    Route::get("/workshop/view","AccountsController@workshop_view")->name("admin.accounts.workshop_view");
    Route::put('/workshop/{workshop}/updateStatus','AccountsController@updateStatusWorkshop')->name('admin.accounts.updateStatusWorkshop');
    Route::delete('/workshop/{workshop_id}', 'AccountsController@delete_workshop')->name('admin.accounts.delete_workshop');
    Route::get('/workshop/edit/{shop_id}','AccountsController@workshop_edit')->name('admin.workshop_edit');
    Route::post('/workshop/update/{shop_id}','AccountsController@workshop_update')->name('admin.workshop_update');
    Route::get('/workshop/edit/view/{shop_id}','AccountsController@workshop_edit_view')->name('admin.workshop_edit_view');
// end workshop
//edirham
    Route::get("/edirham/add","AccountsController@edirham_index")->name("admin.accounts.edirham_index");
    Route::post("/edirham/add-data","AccountsController@edirham_post")->name("admin.accounts.edirham_post");
    Route::get("/edirham/view","AccountsController@edirham_view")->name("admin.accounts.edirham_view");
    Route::put('/edirham/{edirham}/updateStatus','AccountsController@updateStatusEdirham')->name('admin.accounts.updateStatusEdirham');
    Route::delete('/edirham/{edirham}', 'AccountsController@delete_edirham')->name('admin.accounts.delete_edirham');
    Route::get('/edirham/edit/{id}','AccountsController@edirham_edit')->name('admin.edirham_edit');
    Route::post('/edirham/update/{id}','AccountsController@edirham_update')->name('admin.edirham_update');
    Route::get('/edirham/edit/view/{id}','AccountsController@edirham_edit_view')->name('admin.edirham_edit_view');
//end edirham
// maintenance
    Route::get("/maintenance/add","AccountsController@maintenance_index")->name("admin.accounts.maintenance_index");
    Route::post("/maintenance/add-data","AccountsController@maintenance_post")->name("admin.accounts.maintenance_post");
    Route::get("/maintenance/view","AccountsController@maintenance_view")->name("admin.accounts.maintenance_view");
    Route::put('/maintenance/{maintenance}/updateStatus','AccountsController@updateStatusMaintenance')->name('admin.accounts.updateStatusMaintenance');
    Route::delete('/maintenance/{maintenance}', 'AccountsController@delete_maintenance')->name('admin.accounts.delete_maintenance');
    Route::get('/maintenance/edit/{shop_id}','AccountsController@maintenance_edit')->name('admin.maintenance_edit');
    Route::get('/maintenance/edit/view/{shop_id}','AccountsController@maintenance_edit_view')->name('admin.maintenance_edit_view');
    Route::post('/maintenance/update/{shop_id}','AccountsController@maintenance_update')->name('admin.maintenance_update');
//end maintenance
//Company_Expense
    Route::get('/CE/index','ExpenseController@CE_index')->name('admin.CE_index');
    Route::get('/CE/view','ExpenseController@CE_view')->name('admin.CE_view');
    Route::post('/CE/insert','ExpenseController@CE_store')->name('admin.CE_store');
    Route::post('/CE/{id}/update','ExpenseController@CE_update')->name('admin.CE_update');
    Route::post('/CE/{id}/updatestatus','ExpenseController@CE_updatestatus')->name('admin.CE_updatestatus');
    Route::delete('/CE/delete/{id}','ExpenseController@CE_delete')->name('admin.CE_delete');
    Route::get('/CE/edit/{id}','ExpenseController@CE_edit')->name('admin.CE_edit');
    Route::get('/CE/report','ExpenseController@CE_report')->name('admin.CE_report');
    Route::get('/CE/edit/view/{id}','ExpenseController@CE_edit_view')->name('admin.CE_edit_view');
// End Company_Expense
//     WPS
    Route::get('/wps/index','ExpenseController@wps_index')->name('admin.wps_index');
    Route::get('/wps/view','ExpenseController@wps_view')->name('admin.wps_view');
    Route::post('/wps/insert','ExpenseController@wps_store')->name('admin.wps_store');
    Route::post('/wps/{id}/update','ExpenseController@wps_update')->name('admin.wps_update');
    Route::post('/wps/{id}/updatestatus','ExpenseController@wps_updatestatus')->name('admin.wps_updatestatus');
    Route::delete('/wps/delete/{id}','ExpenseController@wps_delete')->name('admin.wps_delete');
    Route::get('/wps/edit/{id}','ExpenseController@wps_edit')->name('admin.wps_edit');
    Route::get('/wps/edit/view/{id}','ExpenseController@wps_edit_view')->name('admin.wps_edit_view');
// End WPS
//ADVANCE & RETURN
    Route::get('/AR/index','ExpenseController@AR_index')->name('admin.AR_index');
    Route::get('/AR/view','ExpenseController@AR_view')->name('admin.AR_view');
    Route::post('/AR/insert','ExpenseController@AR_store')->name('admin.AR_store');
    Route::post('/AR/{id}/update','ExpenseController@AR_update')->name('admin.AR_update');
    Route::post('/AR/{id}/updatestatus','ExpenseController@AR_updatestatus')->name('admin.AR_updatestatus');
    Route::delete('/AR/delete/{id}','ExpenseController@AR_delete')->name('admin.AR_delete');
    Route::get('/AR/edit/{id}','ExpenseController@AR_edit')->name('admin.AR_edit');
    Route::get('/AR/edit/view/{id}','ExpenseController@AR_edit_view')->name('admin.AR_edit_view');
// End ADVANCE & RETURN
    Route::get("/accounts/testing","HomeController@accounts_testing_v1")->name("admin.accounts.testing_v1");
   

});
// end Expense

// NewComer
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:new_comer']
], function(){
    // accounts
    Route::resource('/NewComer', 'NewComerController', [
        'as' => 'admin'
    ]);
    Route::get('/newComer/add','NewComerController@new_comer_form')->name('NewComer.form');
    Route::post('/newComer/insert','NewComerController@insert_newcomer')->name('NewComer.insert');
    Route::get('/newComer/view','NewComerController@new_comer_view')->name('NewComer.view');
    Route::delete('/newComer/delete/{newComer_id}','NewComerController@delete_new_comer')->name('NewCome.delete');
    Route::get('/newComer/Edit/{id}','NewComerController@newComer_edit')->name('NewComer.edit');
    Route::get('/newComer/Edit/view/{id}','NewComerController@newComer_edit_view')->name('NewComer.edit_view');
    Route::post('/newComer/{id}/update', 'NewComerController@updateNewComer')->name('NewComer.updatenewComer');
    Route::get('/newComer/popup/{newComer_id}','NewComerController@newComer_popup')->name('NewComer.popup');
    
});
// End NewComer
// Sim
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:sim']
], function(){
    // accounts
    Route::resource('/Sim', 'SimController', [
        'as' => 'admin'
    ]);

 //    Start Sim Section    
    Route::get('/sim/ajax/data/{sim_id}/{month}','SimController@get_sim_ajax')->name('Sim.get_sim_ajax');
    Route::get('/sim/ajax/get_active_riders/{rider_id}/{month}','SimController@get_active_riders_ajax')->name('Sim.get_active_riders_ajax');

    Route::get('/create/Sim','SimController@add_sim')->name('Sim.new_sim');
    Route::post('/store/Sim','SimController@store_sim')->name('Sim.store_sim');
    Route::get('/view/records/Sim','SimController@view_records_sim')->name('Sim.view_records');
    Route::get('/edit/{id}/Sim','SimController@edit_sim')->name('Sim.edit_sim');
    Route::get('/edit/Sim/view/{id}','SimController@edit_sim_view')->name('Sim.edit_sim_view');
    Route::post('/update/{id}/Sim','SimController@update_sim')->name('Sim.update_sim');
    Route::post('/sim/{sim_id}/updateStatus','SimController@updateStatusSim')->name('Sim.updateStatus_sim');
    Route::delete('/sim/{sim_id}', 'SimController@DeleteSim')->name('Sim.DeleteSim');
    Route::get('/sim/history/rider/{sim_id}','SimController@getRiderHistory')->name('Sim.rider.history');
// End Sim Section  
// Start Sim Transaction Section
    Route::get('/create/Transaction/Sim','SimController@add_simTransaction')->name('SimTransaction.create_sim');
    Route::post('/store/Transaction/Sim','SimController@store_simTransaction')->name('SimTransaction.store_simTransaction');
    Route::get('/view/Transaction/Sim','SimController@view_sim_transaction_records')->name('SimTransaction.view_records');
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
});
// End Sim

// mobile
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:mobile']
], function(){
 //Mobiles
    Route::get('/mobile/create','MobileController@create_mobile_GET')->name('mobile.create_mobile_GET');
    Route::post('/mobile/create','MobileController@create_mobile_POST')->name('mobile.create_mobile_POST');
    Route::get('/mobiles','MobileController@mobiles')->name('mobile.show');
    Route::get('/mobile/{mobile}/edit','MobileController@update_mobile_GET')->name('mobile.edit');
    Route::get('/mobile/{mobile}/edit/view','MobileController@update_mobile_GET_view')->name('mobile.edit_view');
    Route::delete('/mobile/delete/{mobile_id}','MobileController@delete_mobile')->name('Mobile.delete_mobile');
    Route::post('/mobile/{id}/updateStatus','MobileController@updateStatusMobile')->name('Mobile.updatetatus');
    Route::post('/mobile/{id}/update','MobileController@update_mobile')->name('Mobile.update');
//end Mobiles
// mobile installement
    Route::get('/mobile/installment/create','MobileController@create_mobileInstallment')->name('MobileInstallment.create');
    Route::post('/mobile/installment/insert','MobileController@store_mobileInstallment')->name('MobileInstallment.store');
    Route::get('/mobile/installment/show','MobileController@show_mobileInstallmenet')->name('MobileInstallment.show');
    Route::get('/mobile/installment/{mobile}/edit','MobileController@edit_mobileInstallment')->name('MobileInstallment.edit');
    Route::post('/mobile/installment/{id}/update','MobileController@update_mobileInstallment')->name('MobileInstallment.update');
    Route::delete('/mobile/installment/delete/{mobile_id}','MobileController@delete_mobileInstallment')->name('MobileInstallment.delete');
// end mobile installement
// transaction Records
    Route::get('/mobile/transaction/view','MobileController@transaction_view')->name('Mobile.transaction_view');
    Route::post('/mobile/transaction/inline_edit','MobileController@edit_inline_MobileTransaction')->name('Transaction.edit_inline_MobileTransaction');
    Route::get('/mobile/ajax/data/{mobile_id}/{month}','MobileController@consumption_mobile_records')->name('Mobile.consumption_mobile_records');
    Route::post('/mobile/insert/data/consumption','MobileController@consumption_mobile_records_insert')->name('Mobile.consumption_mobile_records_insert');
// end transaction Records
});
// end mobile

// for Admin
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:admin']
], function(){
// employee    
    Route::get('/add/employee','Auth\EmployeeController@showloginform')->name('Employee.showloginform');
    Route::post('/insert/employee','Auth\EmployeeController@insert_employee')->name('Employee.insert_employee');
    Route::get('/show/employee','Auth\EmployeeController@viewEmployee')->name('Employee.viewEmployee');
    Route::get('/show/employee/ajax','Auth\EmployeeController@getEmployee')->name('Employee.getEmployee');
    Route::delete('/delete/employee/{employee_id}','Auth\EmployeeController@deleteEmployee')->name('Employee.deleteEmployee');
    Route::get('/edit/employee/{employee_id}','Auth\EmployeeController@edit_employee')->name('Employee.edit_employee');
    Route::post('/update/employee/{employee_id}','Auth\EmployeeController@update_employee')->name('Employee.update_employee');
// end employee
// login , dashboard
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@login')->name('admin.login');
    Route::get('/logout', 'Auth\LoginController@logout')->name('admin.logout');
    Route::get('/rider/salary','RiderController@rider_salary')->name('Rider.salary');
    Route::get('/', 'HomeController@index')->name('admin.home');
    Route::get('/profile', 'HomeController@profile')->name('admin.profile');
    Route::put('/profile', 'HomeController@updateProfile')->name('admin.profile.update');
//end login , dashboard   
//zomato income
    Route::get("/Salary/accounts/income/zomato/index","AccountsController@income_zomato_index")->name("admin.accounts.income_zomato_index");
    Route::post('/accounts/income/zomato/import','AccountsController@income_zomato_import')->name('admin.accounts.income_zomato_import');
    Route::delete('/accounts/income/zomato/delete','AccountsController@income_zomato_delete')->name('admin.accounts.income_zomato_delete');
//zomato income 
// Client_income
    Route::get('/client_income/index','AccountsController@client_income_index')->name('admin.client_income_index');
    Route::get('/client_income/{client_id}/getRiders','AccountsController@client_income_getRiders')->name('admin.client_income_getRiders');
    Route::get('/client_income/view','AccountsController@client_income_view')->name('admin.client_income_view');
    Route::post('/client_income/insert','AccountsController@client_income_store')->name('admin.client_income_store');
    Route::post('/client_income/{id}/update','AccountsController@client_income_update')->name('admin.client_income_update');
    Route::post('/client_income/{id}/updatestatus','AccountsController@client_income_updatestatus')->name('admin.client_income_updatestatus');
    Route::delete('/client_income/delete/{id}','AccountsController@client_income_delete')->name('admin.client_income_delete');
    Route::get('/client_income/edit/{id}','AccountsController@client_income_edit')->name('admin.client_income_edit');
    Route::get('/client_income/edit/view/{id}','AccountsController@client_income_edit_view')->name('admin.client_income_edit_view');
// end Client_income 
});
// end for Admin
// for Admin global
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
   
], function(){
// login , dashboard
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@login')->name('admin.login');
    Route::get('/logout', 'Auth\LoginController@logout')->name('admin.logout');
    Route::get('/profile', 'HomeController@profile')->name('admin.profile');
    Route::put('/profile', 'HomeController@updateProfile')->name('admin.profile.update');
//end login , dashboard   
    Route::get('/403','HomeController@request403')->name('request.403');
});
// end for Admin global
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:dashboard']
], function(){
    Route::get('/', 'HomeController@index')->name('admin.home');
});






