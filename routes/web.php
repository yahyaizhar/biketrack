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
Route::get('/phpinfo', function () {
    return phpinfo();
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
    Route::get('get/ajax/rider/payouts/days','AjaxController@getRiderPayoutsByDays')->name('admin.getRiderPayoutsByDays');
    Route::get('/range/ajax/adt/{data}','AjaxController@getRiderRangesADT')->name('ajax.adt'); 
    Route::get('/bike_getData','AjaxController@getBikes')->name('bike.bike_show');
    Route::get('/get/salik/bike/ajax/{id}','AjaxController@getSalik_Bike')->name('bike.ajax_salik_bike');
    Route::get('get/ajax/salik/trip_details','AjaxController@getSalikTrip_Details')->name('admin.ajax_details');
    Route::get('/Developer/Salary/ajax','AjaxController@getSalary_by_developer')->name('account.developer_salary_ajax');
    Route::get('/Rider/To/Month/ajax/{rider_id}','AjaxController@getRiderToMonth')->name('account.RiderToMonth_ajax');
    Route::get('/Month/To/Rider/ajax/{month_id}','AjaxController@getMonthToRider')->name('account.MonthToRider_ajax');
    Route::get('/CE/Report/ajax/{month_id}','AjaxNewController@getCE_REPORT')->name('account.getCE_REPORT');
    Route::get('get/ajax/company/accounts','AjaxController@getCompanyAccounts')->name('admin.ajax_company_accounts');
    Route::get('get/ajax/rider/accounts','AjaxController@getRiderAccounts')->name('admin.ajax_rider_accounts');
    Route::get("/accounts/rider/account/{range}","AjaxNewController@getRiderAccounts")->name("admin.accounts.get_rider_account");
    Route::get("/accounts/company/account/{range}","AjaxNewController@getCompanyAccounts")->name("admin.accounts.get_company_account");
    Route::get("/accounts/bike/account/{range}","AjaxNewController@getBikeAccounts")->name("admin.accounts.get_bike_account");
    Route::get("/accounts/company/overall/account/{range}","AjaxNewController@getCompanyOverallAccounts")->name("admin.accounts.getCompanyOverallAccounts");
    Route::get("/accounts/kr-bikes/account/{range}","AjaxNewController@getKR_bikes")->name("admin.accounts.getKR_bikes");
    Route::get('/newComer/view/ajax', 'AjaxController@getNewComer')->name('NewComer.view_ajax');
    Route::get('get/ajax/Sim','AjaxController@getSims')->name('Sim.ajax_sim');
    Route::get('/map/testing/{id}','AjaxController@client_name');
    Route::get('/get/ajax/Transaction/Sim/{month}','AjaxController@getSimTransaction')->name('SimTransaction.ajax_simTransaction');
    Route::get('/get/ajax/Transaction/Mobile/{month}','AjaxNewController@getMobileTransaction')->name('Transaction.getMobileTransaction');
    Route::get('/mobile/installment/data','AjaxController@getMobileInstallment')->name('MobileInstallment.getinstallments');
    Route::get('/mobile/data','AjaxController@getMobiles')->name('mobile.getMobiles');
    Route::get("/client_income/view/data","AjaxNewController@getclient_income")->name("admin.getclient_income");
    Route::get("/accounts/fuel_expense/view/data","AjaxNewController@getFuelExpense")->name("admin.accounts.ajax_fuelExpense");
    Route::get("/accounts/bike_fine/view/ajax","AjaxNewController@getBikeFine")->name("admin.accounts.getBikeFine");
    Route::get("/accounts/id-charges/view/data","AjaxNewController@getIdCharges")->name("admin.accounts.id_charges_view_data");
    Route::get("/accounts/workshop/view/data","AjaxNewController@getWorkShops")->name("admin.accounts.workshop_view_data");
    Route::get("/accounts/edirham/view/data","AjaxNewController@getEdirhams")->name("admin.accounts.edirham_view_data");
    Route::get("/accounts/maintenance/view/data","AjaxNewController@getMaintenances")->name("admin.accounts.maintenance_view_data");
    Route::get("/accounts/CE/view/data","AjaxNewController@getCompanyExpense")->name("admin.getCompanyExpense");
    Route::get("/accounts/wps/view/data","AjaxNewController@getWPS")->name("admin.getWPS");
    Route::get("/accounts/AR/view/data","AjaxNewController@getAR")->name("admin.getAR");
    Route::get('/accounts/income/zomato/ajax/data','AjaxNewController@income_zomato_ajax')->name('admin.accounts.income_zomato_ajax');
    Route::get('/get/ajax/activity/log','AjaxNewController@getActivityLog')->name('admin.getActivityLog');
    Route::get("/accounts/company/bills/{range}","AjaxNewController@getCompanyAccountsBills")->name("admin.accounts.get_company_account_bills");
    Route::get("/accounts/bike/bills/{range}","AjaxNewController@getBikeAccountsBills")->name("admin.accounts.get_bike_account_bills");
    Route::get('/cash/paid/to/rider/{rider_id}','HomeController@cash_paid_to_rider')->name('admin.cash_paid_to_rider');
    Route::get("/accounts/kr_investment/view/data","AjaxNewController@getCompanyInvestment")->name("admin.getCompanyInvestment");
    Route::get("/zomato/salary/sheet/export/ajax/{month_name}","AjaxNewController@zomato_salary_export")->name("admin.zomato_salary_export");
    Route::get("/zomato/profit/sheet/export/ajax/{month_name}/{client_id}","AjaxNewController@zomato_profit_export")->name("admin.zomato_profit_export");
    Route::get('/ajax/generated/rider/bill/status/{month}/{client_id}','AjaxNewController@getGeneratedBillStatus')->name('ajax.getGeneratedBillStatus');
    
    Route::GET('/get/invoices','AjaxNewController@getInvoices')->name('invoice.get_invoices');
    Route::get("/accounts/employee/account/{range}","AjaxNewController@getEmployeeAccounts")->name("admin.accounts.getEmployeeAccounts");
    Route::get("/accounts/employee/bills/{range}","AjaxNewController@getEmployeeAccountsBills")->name("admin.accounts.getEmployeeAccountsBills");
    Route::get("/invoice/ajax/payments/view","AjaxNewController@getInvoicePayments")->name("admin.getInvoicePayments");
    Route::get('/newApprovalComer/view/ajax', 'AjaxController@getApprovalComer')->name('NewComer.view_approval_ajax');

    


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
    Route::get('/rider/{rider}/location', 'RiderController@showRiderLocation')->name('admin.rider.location');
    Route::get('/rider/{rider}/profile', 'RiderController@showRiderProfile')->name('admin.rider.profile');
    // Route::get('/rider/{rider}/viewaccount', 'RiderController@showRiderAccount')->name('admin.rider.account');
    Route::get('/active_riders','RiderController@getRider_active')->name('admin.riders.active');
    Route::get('/rider/{rider}/ridesReport', 'RiderController@showRidesReport')->name('admin.rider.ridesReport');
    Route::delete('/ridesReportRecord/{record}', 'RiderController@deleteRidesReportRecord')->name('admin.rider.ridesReport.delete');
    Route::post('/rider/{rider}/sendMessage', 'RiderController@sendSMS')->name('admin.rider.sendSMS');
    Route::post('/rider/{rider}/updateStatus', 'RiderController@updateStatus')->name('admin.rider.updateStatus');  
    Route::get('/rider/client_history/{id}',"RiderController@client_history")->name('Client.client_history');
    Route::get('/rider/spell/time/{id}',"RiderController@Spell_time")->name('Rider.spell_time');
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
    Route::delete('/delete/Rider/{rider_id}','RiderController@destroy');
    Route::delete('/rider/{rider_id}/removeBike/{bike_id}','ClientController@deletebikeprofile');
    Route::get('/rider/complete/detail/view','RiderDetailController@view_detail')->name('rider.view_detail');
    Route::get('/rider/detail/ajax/{id}/{month}/{according_to}','RiderDetailController@get_data_ajax_detail')->name('ajax.get_data_ajax_detail');
});
// end Riders
// clients
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:clients']
], function(){
    Route::post('/update/client/riders/{rider_id}','RiderController@update_ClientRiders')->name('ClientRiders.admin.update');
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
    Route::get('/change/clients/{rider_id}/{client_history_id}/history/dates','ClientController@client_history_dates')->name('admin.client_history_dates');
    Route::resource('emails', 'ClientEmailController', [ 
        'as' => 'admin'
    ]);
    Route::get('update/extra/fields/adt/performance/{feid}/{start_date}/{end_date}','RiderController@update_extra_adt')->name('admin.update_extra_adt');
    Route::get('/client/ranges/adt','RiderController@Rider_Range_ADT')->name('admin.ranges.adt'); 
//import Zomato
    Route::post('/import/zomato','RiderController@import_zomato')->name('import.zomato');
    Route::delete('/delete/last/import','RiderController@delete_lastImport')->name('delete.import_data');
    Route::get("/zomato/salary/sheet/export","AccountsController@zomato_salary_sheet_export")->name("admin.zomato_salary_sheet_export");
    Route::get('/zomato/riders/payout/by/days','AccountsController@view_riders_payouts_days')->name('zomato.view_riders_payouts_days');
    Route::post('/import/riders/payouts/days','AccountsController@import_rider_daysPayouts')->name('import.import_rider_daysPayouts');
    Route::get("/rider/hours/trips/details/{month}/{rider_id}","AccountsController@hours_trips_details");
    Route::get("/rider/week/days/off/status/{month}/{rider_id}/{day}","AccountsController@weekly_days_off");
    Route::get("/rider/week/days/sync/data/{month}/{rider_id}/{weekly_off_day}/{absent_days}/{weekly_off}/{extra_day}/","AccountsController@weekly_days_sync_data"); 
//ends import Zomato

//payout method
Route::POST("/client/add_payout_method","ClientController@add_payout_method")->name("admin.add_payout_method");
//payout method
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
    Route::post('/bike/{bike}/updateStatus', 'ClientController@updateStatusbike')->name('bike.updateStatusbike');
    Route::delete('/bike/{bike_id}', 'ClientController@mutlipleDeleteBike')->name('bike.mutlipleDeleteBike');
    Route::get('/bike/Edit/{id}','bikeController@bike_edit')->name('Bike.edit_bike');
    Route::post('/bike/update/{id}','bikeController@bike_update')->name('Bike.bike_update');
    Route::get('/riders/{rider}/history','ClientController@Bike_assigned_to_riders_history')->name('Bike.assignedToRiders_History');
    Route::get('/bike/{bike_id}/history','ClientController@rider_history')->name('bike.rider_history');
    Route::get('/change/{rider_id}/history/{bike_id}','ClientController@change_dates_history')->name('admin.change_dates_history');
    Route::get('/bike/{bike_id}/profile/{rider_id}','ClientController@bike_profile')->name('bike.bike_profile');
    Route::get('view/bike/salik/{id}','SalikController@bike_salik')->name('bike.bike_salik');
    Route::get('view/rider/salik/{id}','SalikController@rider_salik')->name('rider.rider_salik');
    Route::get('/bike/rent/view','bikeController@create_bike_rent')->name('admin.create_bike_rent');
    Route::post('/insert/bike/rent','bikeController@post_bike_rent')->name('admin.post_bike_rent');
    Route::post('/get/company/insurance/name/','bikeController@insurance_co_name')->name('bike.insurance_co_name');
    Route::get('/assigned/company/{bike}', 'bikeController@give_bike_to_company')->name('bike.give_bike_to_company');
    Route::post('/is/given/bike/to/company/{bike_id}','bikeController@is_given_bike_status')->name('bike.is_given_bike_status');
    Route::get('/bike/deactive/{rider_id}/date/{bike_id}','bikeController@deactive_date')->name('admin.deactive_date');
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
    Route::get('/salik/ajax/get_active_bikes/{rider_id}/{month}/{according_to}','SalikController@get_active_bikes_ajax_salik')->name('Saik.get_active_bikes_ajax_salik');
    Route::get('/sim/ajax/get_active_sims/{id}/{month}/{according_to}','SalikController@get_active_sims_ajax_salik')->name('Saik.get_active_sims_ajax_salik');
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
    Route::get("/delete/accounts/rows","AccountsController@delete_account_rows")->name("admin.delete_account_rows");
    Route::get("/Salary/accounts/bike/account","AccountsController@bike_account")->name("admin.accounts.bike_account");
    Route::get('/rider/accounts/{id}/updateStatus','AccountsController@updatePaymentStatus')->name('Rider.updatePaymentStatus');

    Route::get("/accounts/company/debits/get_salary_deduction/{month}/{rider_id}","AccountsController@get_salary_deduction")->name("admin.accounts.get_salary_deduction");
    
    Route::get("/Salary/accounts/rider/expense","AccountsController@rider_expense_get")->name("admin.accounts.rider_expense_get");
    Route::post("/accounts/rider/expense/add","AccountsController@rider_expense_post")->name("admin.accounts.rider_expense_post");
    Route::get('/rider/expense/bonus/','RiderDetailController@rider_expense_bonus')->name('expense.rider_expense_bonus');
    Route::get('/rider/expense/discipline/','RiderDetailController@rider_expense_discipline')->name('expense.rider_expense_discipline');
    Route::post("/accounts/rider/cash/paid","RiderDetailController@cash_paid")->name("admin.cash_paid");
    Route::post("/accounts/rider/cash/credit","RiderDetailController@cash_credit_rider")->name("admin.cash_credit_rider");
    Route::post("/accounts/rider/cash/debit","RiderDetailController@cash_debit_rider")->name("admin.cash_debit_rider");

    Route::post("/accounts/company/profit/add","AccountsController@add_company_profit")->name("admin.accounts.add_company_profit");
    Route::get("/company/overall/report","AccountsController@company_overall_report")->name("admin.accounts.company_overall_report");
    Route::put('/bill/payment/{rider_id}/updateStatus/{month}/{type}','AccountsController@updateBillPaymentStatus')->name('admin.updateBillPaymentStatus');

    // investment 
    Route::get("/kr_investment/add","AccountsController@kr_investment_index")->name("admin.accounts.kr_investment_index");
    Route::post("/kr_investment/add-data","AccountsController@kr_investment_post")->name("admin.accounts.kr_investment_post");
    Route::get("/kr_investment/view","AccountsController@kr_investment_view")->name("admin.accounts.kr_investment_view");
    Route::POST('/kr_investment/{kr_investment}/updatestatus','AccountsController@updateStatusKr_investment')->name('admin.accounts.updateStatusKr_investment');
    Route::delete('/kr_investment/{kr_investment}', 'AccountsController@delete_kr_investment')->name('admin.accounts.delete_kr_investment');
    Route::get('/kr_investment/edit/{shop_id}','AccountsController@kr_investment_edit')->name('admin.kr_investment_edit');
    Route::get('/kr_investment/edit/view/{shop_id}','AccountsController@kr_investment_edit_view')->name('admin.kr_investment_edit_view');
    Route::post('/kr_investment/update/{shop_id}','AccountsController@kr_investment_update')->name('admin.kr_investment_update');
    //end investment
    Route::get('/salary/slip/for/riders','AccountsController@salary_slip')->name('account.salary_slip');
    Route::post("/accounts/rider/remaining_salary/add","AccountsController@rider_remaining_salary_add")->name("admin.accounts.rider_remaining_salary_add");
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

Route::group([
    'prefix' => 'admin', 
    'namespace' => 'Admin',
    'middleware' => ['roles:employee']
], function(){
    Route::get("/employee/salary_generate","EmployeeController@salary_generated")->name("employee.salary_generated");
    Route::get("/employee/bonus","EmployeeController@employee_bonus");
    Route::get("/employee/fine","EmployeeController@employee_fine");
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
    Route::get('/fuel/expense/select/riders/bike/{rider_id}/{bike_id}','AccountsController@fuel_rider_selector')->name('fuel.fuel_rider_selector');
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
    Route::get("/company/expense/investment/detail/{month}","ExpenseController@get_investment_detail")->name("admin.get_investment_detail");
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
//Bike Fine
    Route::get('/BF/index','KRController@BF_index')->name('admin.BF_index');
    Route::get('/BF/view','KRController@BF_view')->name('admin.BF_view');
    Route::post('/BF/insert','KRController@BF_store')->name('admin.BF_store');
    Route::post('/BF/{id}/update','KRController@BF_update')->name('admin.BF_update');
    Route::delete('/BF/delete/{id}','KRController@BF_delete')->name('admin.BF_delete');
    Route::get('/BF/edit/{id}','KRController@BF_edit')->name('admin.BF_edit');
    Route::get('/BF/edit/view/{id}','KRController@BF_edit_view')->name('admin.BF_edit_view');
    Route::get('/fine/paid/Rider/{rider_id}/{bike_fine_id}/{amount}/{month}','KRController@paid_fine_by_rider');
// End Bike Fine
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
    Route::get('/newComer/approval','NewComerController@new_comer_approval_view')->name('NewComer.approval');
    Route::delete('/newComer/delete/{newComer_id}','NewComerController@delete_new_comer')->name('NewCome.delete');
    Route::get('/newComer/Edit/{id}','NewComerController@newComer_edit')->name('NewComer.edit');
    Route::get('/newComer/Edit/view/{id}','NewComerController@newComer_edit_view')->name('NewComer.edit_view');
    Route::post('/newComer/{id}/update', 'NewComerController@updateNewComer')->name('NewComer.updatenewComer');
    Route::get('/newComer/popup/{newComer_id}','NewComerController@newComer_popup')->name('NewComer.popup');
    
});
// End NewComer
// Activity
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:activity']
], function(){
    Route::get('/activity/view','KRController@activity_view')->name('admin.activity.view');
    Route::delete('/delete/activity/{id}','KRController@delete_activity_log')->name('admin.delete_activity_log');
    Route::get('/tax/KR','KRController@gov_tax')->name('admin.gov_tax');
});
// End Activity
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
    Route::get('/sim/ajax/data/{sim_id}/{month}/{rider_id}','SimController@get_sim_ajax')->name('Sim.get_sim_ajax');
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
    Route::get('/change/sim/{rider_id}/history/{assign_sim_id}','SimController@sim_dates_History')->name('Sim.sim_dates_History');
    Route::get('/sim/deactive/{rider_id}/date/{sim_id}','SimController@sim_deactive_date')->name('admin.sim_deactive_date');
    Route::get('/sim/allowed/balance/{rider_id}/update/{sim_id}','SimController@update_allowed_abalance')->name('Sim.update_allowed_abalance');
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
    Route::get("/assign/client/rider_id/{p_id}/{feid}/{rider_id}","AccountsController@assign_client_rider_id")->name("income.assign_client_rider_id");
    Route::get("/Salary/accounts/income/zomato/index","AccountsController@income_zomato_index")->name("admin.accounts.income_zomato_index");
    Route::post('/accounts/income/zomato/import','AccountsController@income_zomato_import')->name('admin.accounts.income_zomato_import');
    Route::delete('/accounts/income/zomato/delete','AccountsController@income_zomato_delete')->name('admin.accounts.income_zomato_delete');

    Route::get('/get_previous_month', 'AccountsController@getPreviousMonthIncomeZomato')->name('income_zomato.get_previous_month');
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

// company profit
    Route::get('/client/profit/sheet/{client_id}','ClientController@profit_client')->name('client.profit_sheet_view');
    Route::get('/client/total/expense/sheet/{client_id}','RiderDetailController@client_total_expense')->name('client.client_total_expense');
    Route::get('/client/month/record/{month}/{client}','RiderDetailController@summary_month');
     
    Route::get('/generated/month/bills','BillsController@rider_generated_bills')->name('bills.rider_generated_bills'); 
});
// end for Admin
// for Admin global
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:Custom_Auth']
], function(){
    Route::get('/profile', 'HomeController@profile')->name('admin.profile');
    Route::put('/profile', 'HomeController@updateProfile')->name('admin.profile.update');
    Route::get('/403','HomeController@request403')->name('request.403');
});

Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function(){
// login , dashboard
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@login')->name('admin.login');
    Route::get('/logout', 'Auth\LoginController@logout')->name('admin.logout');
    Route::get('/NewComer/add','GuestController@newComer_view')->name('guest.newComer_view');
});
// end for Admin global
 
Route::group([
    'prefix' => 'guest',
], function(){
// Guest routes
    Route::get('/newcomer/add','GuestController@newComer_view')->name('guest.newComer_view');
    Route::post('/newcomer/store','GuestController@newComer_add')->name('guest.newComer_add');
});
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:dashboard']
], function(){
    Route::get('/', 'HomeController@index')->name('admin.home');
    Route::post('/cash/paid/rider/{id}','HomeController@cash_paid_rider')->name('admin.cash_paid_rider');
});
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles:admin']
], function(){
   Route::get('/add/invoice/tax','InvoiceController@add_invoice')->name('tax.add_invoice');
   Route::POST('/add/invoice','InvoiceController@add_invoice_post')->name('tax.add_invoice_post');
   

   Route::get('/invoice/tax/ajax/get_clients_details/{client_id}/{month}','InvoiceController@get_ajax_client_details')->name('tax.get_ajax_client_details');
   Route::get('/invoice/view','InvoiceController@view_invoices')->name('tax.view_invoices');
   Route::get('/invoice/tax_method/add','InvoiceController@add_tax_method')->name('invoice.add_tax_method');
   Route::post('/invoice/tax_method/store','InvoiceController@store_tax_method')->name('invoice.store_tax_method');
   Route::get('/invoice/bank_account/add','InvoiceController@add_bank_account')->name('invoice.add_bank_account');
   Route::post('/invoice/bank_account/store','InvoiceController@store_bank_account')->name('invoice.store_bank_account');

   Route::get('/invoice/get/open/{client_id}','InvoiceController@getOpenIvoices')->name('invoice.getOpenIvoices');
   Route::post('/invoice/payment/save','InvoiceController@save_payment')->name('invoice.save_payment');
  
   Route::get('/invoice/payments/view','InvoiceController@invoive_payments')->name('invoice.invoive_payments');

   Route::get('/companyinfo','InvoiceController@company_info')->name('invoice.company_info');
   Route::post('/companyinfo/store','InvoiceController@company_info_store')->name('invoice.company_info_store');
});   








