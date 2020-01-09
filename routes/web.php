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
    return redirect(route('admin.home'));
});
Route::get('/mobileapp', function () {
    return view('welcome');
});

					 
   

// Ajax Routes (no middleware)
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function(){
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@login')->name('admin.login');
    Route::get('/logout', 'Auth\LoginController@logout')->name('admin.logout');

    Route::get('/riders/data', 'AjaxController@getRiders')->name('admin.riders.data');
    Route::get('/riders/{rider}/ridesReport/data', 'AjaxController@getRidesReport')->name('admin.ridesReport.data');
    Route::get('get/ajax/rider/active','AjaxController@getActiveRiders')->name('admin.ajax_active_rider');
    Route::get('/riders/details/data', 'AjaxController@getRidersDetails')->name('admin.riders_detail.data');
    Route::get('/clients/data', 'AjaxController@getClients')->name('admin.clients.data');
    Route::get('/clients/data/active', 'AjaxController@getActiveClients')->name('admin.clients.data.active');																											 
    Route::get('get/ajax/rider/performance','AjaxController@getRiderPerformance')->name('admin.ajax_performance');
    Route::get('get/ajax/rider/payouts/days','AjaxController@getRiderPayoutsByDays')->name('admin.getRiderPayoutsByDays');
    Route::get('/range/ajax/adt/{data}','AjaxController@getRiderRangesADT')->name('ajax.adt'); 
    Route::get('/bike_getData','AjaxController@getBikes')->name('bike.bike_show');
    Route::get('/bike_getData/active','AjaxController@getActiveBikes')->name('bike.bike_show.active');
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
	Route::get('get/ajax/Sim/active','AjaxController@getActiveSims')->name('Sim.ajax_sim.active');
    Route::get('/map/testing/{id}','AjaxController@client_name');
    Route::get('/get/ajax/Transaction/Sim/{month}','AjaxController@getSimTransaction')->name('SimTransaction.ajax_simTransaction');
    Route::get('/get/ajax/Transaction/Mobile/{month}','AjaxNewController@getMobileTransaction')->name('Transaction.getMobileTransaction');
    Route::get('/mobile/installment/data','AjaxController@getMobileInstallment')->name('MobileInstallment.getinstallments');
    Route::get('/mobile/data','AjaxController@getMobiles')->name('mobile.getMobiles');
    Route::get("/Mobile/ajax/sellers/view","AjaxNewController@getSellers")->name("Mobile.getSellers");
    Route::get("/Mobile/ajax/accessories/view","AjaxNewController@getAccessory")->name("Mobile.getAccessory");
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
																													   
    Route::get("/accounts/kr_investment/view/data","AjaxNewController@getCompanyInvestment")->name("admin.getCompanyInvestment");
    Route::get("/zomato/salary/sheet/export/ajax/{month_name}/{client_id}","AjaxNewController@zomato_salary_export")->name("admin.zomato_salary_export");
    Route::get("/clients/salary/sheet/export/ajax/{month_name}","AjaxNewController@client_salary_export")->name("admin.client_salary_export");
    
    Route::get("/zomato/profit/sheet/export/ajax/{month_name}/{client_id}","AjaxNewController@zomato_profit_export")->name("admin.zomato_profit_export");
    Route::get('/ajax/generated/rider/bill/status/{month}/{client_id}','AjaxNewController@getGeneratedBillStatus')->name('ajax.getGeneratedBillStatus');
    Route::GET('/get/invoices','AjaxNewController@getInvoices')->name('invoice.get_invoices'); 
    Route::get("/accounts/employee/account/{range}","AjaxNewController@getEmployeeAccounts")->name("admin.accounts.getEmployeeAccounts");
    Route::get("/accounts/employee/bills/{range}","AjaxNewController@getEmployeeAccountsBills")->name("admin.accounts.getEmployeeAccountsBills");
    Route::get("/invoice/ajax/payments/view","AjaxNewController@getInvoicePayments")->name("admin.getInvoicePayments");
    Route::get('/newApprovalComer/view/ajax/{id}', 'AjaxController@getApprovalComer')->name('NewComer.view_approval_ajax');
    Route::get('ajax/view_routes','AjaxController@getWebRoutes')->name('admin.view_routes_ajax'); //ok [for developer]

    Route::get('/salik/ajax/get_active_riders/{rider_id}/{month}/{according_to}','SalikController@get_active_riders_ajax_salik')->name('Saik.get_active_riders_ajax_salik');
    Route::get('/salik/ajax/get_active_bikes/{rider_id}/{month}/{according_to}','SalikController@get_active_bikes_ajax_salik')->name('Saik.get_active_bikes_ajax_salik');
    Route::get('/sim/ajax/get_active_sims/{id}/{month}/{according_to}','SalikController@get_active_sims_ajax_salik')->name('Saik.get_active_sims_ajax_salik');
    Route::get('/sim/ajax/data/{sim_id}/{month}/{rider_id}','SimController@get_sim_ajax')->name('Sim.get_sim_ajax');
    Route::get('/sim/ajax/get_active_riders/{rider_id}/{month}','SimController@get_active_riders_ajax')->name('Sim.get_active_riders_ajax');

    Route::get('/get_previous_month/{month}', 'AccountsController@getPreviousMonthIncomeZomato')->name('income_zomato.get_previous_month');

    Route::get("/accounts/company/expense/investment/detail/{month}","ExpenseController@get_investment_detail")->name("admin.get_investment_detail");

    Route::get("/accounts/company/debits/get_salary_deduction/{month}/{rider_id}","AccountsController@get_salary_deduction")->name("admin.accounts.get_salary_deduction");

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
		 
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['roles']
], function(){
    //dashboard
    Route::get('/', 'HomeController@index')->name('admin.home'); //--ok
    //dashboard
// Riders
    /*[rider-view]*/Route::get('/riders', 'RiderController@index')->name('admin.riders.index');
    /*[rider-create]*/Route::get('/riders/create', 'RiderController@create')->name('admin.riders.create');
    /*[rider-create]*/Route::post('/riders', 'RiderController@store')->name('admin.riders.store');
	   
    /*[rider-edit]*/Route::get('/riders/{rider}/edit', 'RiderController@edit')->name('admin.riders.edit');
    /*[rider-edit]*/Route::PUT('/riders/{rider}', 'RiderController@update')->name('admin.riders.update'); // update rider
    /*[rider-Delete]*/Route::DELETE('/riders/{rider}', 'RiderController@destroy')->name('admin.riders.destroy');//ajax_route ////ok
    Route::get('/rider/{rider}/location', 'RiderController@showRiderLocation')->name('admin.rider.location'); //mobile_route //not_using
    /*[rider-profile]*/Route::get('/rider/{rider}/profile', 'RiderController@showRiderProfile')->name('admin.rider.profile');
    /*[rider-Active riders]*/Route::get('/active_riders','RiderController@getRider_active')->name('admin.riders.active');
    Route::get('/rider/{rider}/ridesReport', 'RiderController@showRidesReport')->name('admin.rider.ridesReport'); //mobile_route //not_using
    Route::delete('/ridesReportRecord/{record}', 'RiderController@deleteRidesReportRecord')->name('admin.rider.ridesReport.delete'); //mobile_route //not_using
    Route::post('/rider/{rider}/sendMessage', 'RiderController@sendSMS')->name('admin.rider.sendSMS'); //trash
    /*[rider-active/inactive]*/Route::post('/rider/{rider}/updateStatus', 'RiderController@updateStatus')->name('admin.rider.updateStatus');//ajax_route //active/inactive 
    /*[rider-client history]*/Route::get('/rider/client_history/{id}',"RiderController@client_history")->name('Client.client_history');
    /*[rider-Duration time]*/Route::get('/rider/spell/time/{id}',"RiderController@Spell_time")->name('Rider.spell_time');
//  map Routes
    Route::get('/rider/assign-area', 'HomeController@assign_area')->name('admin.assignArea');   ///not_using
    Route::post('/rider/assign-area/assign', 'HomeController@assign_area_POST')->name('admin.post.assignArea');  ///not_using
    Route::get('/assign/area/to/rider/{id}','HomeController@assign_area_to_rider')->name('admin.area_assign_to_rider');   ///not_using
// end map Routes
//   rider further details
    Route::get('/rider/detail','RiderController@rider_details')->name('admin.Rider_details');//not_using
															  
    /*[others-livemap]*/Route::get('/livemap', 'HomeController@livemap')->name('admin.livemap');   ///not added
// end rider further details
    Route::delete('/rider/{rider_id}/removeBike/{bike_id}','ClientController@deletebikeprofile')->name('admin.delete_bike_profile'); //trash
    /*[rider-detail view]*/Route::get('/rider/complete/detail/view','RiderDetailController@view_detail')->name('rider.view_detail');////ok
    /*[rider-detail view]*/Route::get('/rider/detail/ajax/{id}/{moPnth}/{according_to}','RiderDetailController@get_data_ajax_detail')->name('ajax.get_data_ajax_detail');//ajax_route ////ok

// end Riders
// clients
    /*[clients-change feid]*/Route::post('/update/client/riders/{rider_id}','RiderController@update_ClientRiders')->name('ClientRiders.admin.update');//ajax_route //changing feid on admin.clients.riders route ////ok
    /*[clients-rider performance]*/Route::get('/client/rider/performance','RiderController@RiderPerformance')->name('admin.riderPerformance');////ok
    Route::resource('/clients', 'ClientController', [
        'as' => 'admin'
    ]);
    Route::get('/client/{client}/profile', 'ClientController@showClientProfile')->name('admin.client.profile');//not_using ////ok
	/*[clients-View Active clients]*/Route::get('/get/active/client','ClientController@get_active_clients')->name('admin.get_active_clients');
    /*[clients-rider history]*/Route::get('/client/{client}/riders', 'ClientController@showRiders')->name('admin.clients.riders');////ok
    /*[Clients-assign rider]*/Route::get('/client/{client}/assignRider', 'ClientController@assignRiders')->name('admin.clients.assignRiders');////ok
    /*[Clients-assign rider]*/Route::put('/client/{client}/assignRider', 'ClientController@updateAssignedRiders')->name('admin.clients.assignRiders');//ajax_route ////ok
    /*[Clients-unassign rider]*/Route::delete('/client/{client}/removeRider/{rider}', 'ClientController@removeRiders')->name('admin.clients.removeRiders');//ajax_route ////ok
    /*[Clients-active/inactive]*/Route::post('/client/{client}/updateStatus', 'ClientController@updateStatus')->name('admin.client.updateStatus');//ajax_route ////ok
    Route::post('/client/mutlipleDelete', 'ClientController@mutlipleDelete')->name('admin.client.mutlipleDelete');//not_using ////ok
    /*[clients-history update assign/unassign dates]*/Route::get('/change/clients/{rider_id}/{client_history_id}/history/dates','ClientController@client_history_dates')->name('admin.client_history_dates');//ajax_route ////ok
    Route::resource('emails', 'ClientEmailController', [ 
        'as' => 'admin'
    ]);
    /*[clients- ADT update additional data]*/Route::get('update/extra/fields/adt/performance/{feid}/{start_date}/{end_date}','RiderController@update_extra_adt')->name('admin.update_extra_adt');//ajax_route ////ok
    /*[clients-ADT]*/Route::get('/client/ranges/adt','RiderController@Rider_Range_ADT')->name('admin.ranges.adt'); 
//import Zomato
    /*[clients- Zomato import rider performance]*/Route::post('/import/zomato','RiderController@import_zomato')->name('import.zomato');
    /*[clients- performance delete last import]*/Route::delete('/delete/last/import','RiderController@delete_lastImport')->name('delete.import_data');
    /*[clients- View salary sheet]*/Route::get("/client/{cleint_id}/salarysheet","AccountsController@zomato_salary_sheet_export")->name("admin.zomato_salary_sheet_export");
    /*[clients- View all clients salary sheet]*/Route::get("/all_clients/salarysheet","AccountsController@all_clients_salary_sheet_export")->name("admin.all_clients_salary_sheet_export");
    
    /*[rider- View attendance data]*/Route::get('/zomato/riders/payout/by/days','AccountsController@view_riders_payouts_days')->name('zomato.view_riders_payouts_days');
    /*[rider- Import attendance data]*/Route::post('/import/riders/payouts/days','AccountsController@import_rider_daysPayouts')->name('import.import_rider_daysPayouts');
    /*[rider- view attendance]*/Route::get("/rider/hours/trips/details/{month}/{rider_id}","AccountsController@hours_trips_details")->name('attendance.get_attendance_ajax');
    Route::get("/rider/week/days/off/status/{month}/{rider_id}/{day}","AccountsController@weekly_days_off"); //not_using
    Route::get("/rider/week/days/sync/data/{month}/{rider_id}/{weekly_off_day}/{absent_days}/{weekly_off}/{extra_day}/","AccountsController@weekly_days_sync_data"); //not_using
//ends import Zomato

    /*[rider- resync attendace data]*/Route::post('/resync_attendance_data','AccountsController@resync_attendance_data')->name('import.resync_attendance_data');
    //payout method
    /*[clients - Add payout method]*/Route::POST("/client/add_payout_method","ClientController@add_payout_method")->name("admin.add_payout_method");
    //payout method
    //salary method
    /*[clients - Add salary method]*/Route::POST("/client/add_salary_method","ClientController@add_salary_method")->name("admin.add_salary_method");
//salary method
// clients

// Bike
    /*[bike - add bike]*/Route::get('/bike_login','bikeController@bike_login')->name('bike.bike_login');
    /*[bike - add bike]*/Route::post('/bike_create','bikeController@create_bike')->name('bike.bike_create');																			
    /*[bike - view all bike]*/Route::get('/bike_view','bikeController@bike_view')->name('bike.bike_view');
	/*[bike - view active bike]*/Route::get('/active/bike_view','bikeController@bike_view_active')->name('bike.bike_view_active');
    Route::get('/bike/{bike}/assigned', 'ClientController@bike_assigned_show')->name('bike.bike_assigned'); //not_using
    /*[rider - assign bike]*/Route::get('/bike/{id}/assignRider', 'ClientController@bike_assigned_toRider')->name('bike.bike_assignRiders');
    /*[rider - assign bike]*/Route::put('/bike/{rider}/assignRider', 'ClientController@updateAssignedBike')->name('bike.bike_assignRiders');
    /*[bike - Active/Inactive]*/Route::post('/bike/{bike}/updateStatus', 'ClientController@updateStatusbike')->name('bike.updateStatusbike');
    Route::delete('/bike/{bike_id}', 'ClientController@mutlipleDeleteBike')->name('bike.mutlipleDeleteBike');//not_using
    /*[bike - edit bike]*/Route::get('/bike/Edit/{id}','bikeController@bike_edit')->name('Bike.edit_bike');
    /*[bike - edit bike]*/Route::post('/bike/update/{id}','bikeController@bike_update')->name('Bike.bike_update');
    /*[rider - bike history]*/Route::get('/riders/{rider}/history','ClientController@Bike_assigned_to_riders_history')->name('Bike.assignedToRiders_History');
    /*[bike - rider history]*/Route::get('/bike/{bike_id}/history','ClientController@rider_history')->name('bike.rider_history');
    /*[bike - change assign/unassign dates]*/Route::get('/change/{rider_id}/history/{bike_id}','ClientController@change_dates_history')->name('admin.change_dates_history');
    Route::get('/bike/{bike_id}/profile/{rider_id}','ClientController@bike_profile')->name('bike.bike_profile'); ///not_using
    /*[bike - view salik]*/Route::get('view/bike/salik/{id}','SalikController@bike_salik')->name('bike.bike_salik');
    /*[rider - view salik]*/Route::get('view/rider/salik/{id}','SalikController@rider_salik')->name('rider.rider_salik');
    /*[bike - add bike rent]*/Route::get('/bike/rent/view','bikeController@create_bike_rent')->name('admin.create_bike_rent');
    /*[bike - add bike rent]*/Route::post('/insert/bike/rent','bikeController@post_bike_rent')->name('admin.post_bike_rent');
    /*[bike - add Insurance Company]*/Route::post('/get/company/insurance/name/','bikeController@insurance_co_name')->name('bike.insurance_co_name');
    /*[bike - give rental bike to company]*/Route::get('/assigned/company/{bike}', 'bikeController@give_bike_to_company')->name('bike.give_bike_to_company'); 
    /*[bike - give rental bike to company]*/Route::post('/is/given/bike/to/company/{bike_id}','bikeController@is_given_bike_status')->name('bike.is_given_bike_status');
    /*[rider - unassign bike]*/Route::get('/bike/deactive/{rider_id}/date/{bike_id}','bikeController@deactive_date')->name('admin.deactive_date'); ////ok
    /*[Bike - View bike]*/Route::get('/bike/Edit/{id}/view','bikeController@bike_edit_view')->name('Bike.bike_edit_view');
    // salik 
    /*[bike - View salik]*/Route::get("/salik","SalikController@import_salik_data")->name("admin.salik");
    /*[bike - Import Salik]*/Route::post('/import/salik','SalikController@import_Salik')->name('import.salik');
    /*[bike - salik Delete last import]*/Route::delete('/delete/last/import/salik','SalikController@delete_lastImportSalik')->name('delete.import_salik');

    /*[bike - add salik]*/Route::get('/add/salik','SalikController@add_salik')->name("salik.add_salik");
    /*[bike - add salik]*/Route::get('/store/salik/{rider_id}','SalikController@store_salik')->name("salik.store_salik");
    /*[bike - add salik]*/Route::post('/insert/salik','SalikController@insert_salik')->name('Saik.insert_salik');
    
// end salik   
   
// Bike

// Accounts
    /*[accounts - generate salary]*/Route::get('/Add/Salary','AccountsController@add_new_salary_create')->name('account.new_salary');
    /*[accounts - generate salary]*/Route::post('/Salary/Added','AccountsController@new_salary_added')->name('account.added_salary');
    Route::get('/rider/salary','RiderController@rider_salary')->name('Rider.salary');//not_using
	
    Route::delete('/month/{month_id}', 'AccountsController@DeleteMonth')->name('account.DeleteMonth');//not_using
    /*[accounts - view salary by month]*/Route::get('/Month/Salary','AccountsController@salary_by_month_create')->name('account.month_salary');
    Route::post('/month/{month_id}/updateStatus', 'AccountsController@updateStatusmonth')->name('account.updateStatusmonth');//not_using
    Route::get('/month/Edit/{month_id}','AccountsController@month_edit')->name('account.edit_month');//not_using
    Route::get('/month/Edit/view/{month_id}','AccountsController@month_edit_view')->name('account.edit_month_view');//not_using
    Route::post('/month/update/{month_id}','AccountsController@month_update')->name('account.month_update');//not_using
    
    /*[accounts - view salary by rider]*/Route::get('/Developer/Salary','AccountsController@salary_by_developer_create')->name('account.developer_salary');
    Route::delete('/developer/{developer_id}', 'AccountsController@DeleteDeveloper')->name('account.DeleteDeveloper');//not_using
    Route::post('/developer/{developer_id}/updateStatus', 'AccountsController@updateStatusdeveloper')->name('account.updateStatusdeveloper');//not_using
    Route::get('/developer/Edit/{developer_id}','AccountsController@developer_edit')->name('account.edit_developer');//not_using
    Route::get('/developer/Edit/view/{developer_id}','AccountsController@developer_edit_view')->name('account.edit_developer_view');//not_using
    Route::post('/developer/update/{developer_id}','AccountsController@developer_update')->name('account.developer_update');//not_using

    /*[accounts - rider account]*/ Route::get("/Salary/accounts/rider/account","AccountsController@rider_account")->name("admin.accounts.rider_account");//imp
    /*[accounts - company account]*/Route::get("/Salary/accounts/company/account","AccountsController@company_account")->name("admin.accounts.company_account");//imp
    /*[accounts - delete rider account]*/Route::get("/delete/accounts/rows","AccountsController@delete_account_rows")->name("admin.delete_account_rows");//imp
    
    /*[accounts - bike account]*/Route::get("/Salary/accounts/bike/account","AccountsController@bike_account")->name("admin.accounts.bike_account");
    Route::get('/rider/accounts/{id}/updateStatus','AccountsController@updatePaymentStatus')->name('Rider.updatePaymentStatus');//not_using

    
    
    /*[accounts - add rider expense]*/Route::get("/Salary/accounts/rider/expense","AccountsController@rider_expense_get")->name("admin.accounts.rider_expense_get");
    /*[accounts - add rider expense]*/Route::post("/accounts/rider/expense/add","AccountsController@rider_expense_post")->name("admin.accounts.rider_expense_post");
    /*[accounts - add bonus]*/Route::get('/rider/expense/bonus/','RiderDetailController@rider_expense_bonus')->name('expense.rider_expense_bonus');
    /*[accounts - add dicipline fine]*/Route::get('/rider/expense/discipline/','RiderDetailController@rider_expense_discipline')->name('expense.rider_expense_discipline');
    /*[accounts - pay cash to rider]*/Route::post("/accounts/rider/cash/paid","RiderDetailController@cash_paid")->name("admin.cash_paid");
    Route::post("/accounts/rider/cash/credit","RiderDetailController@cash_credit_rider")->name("admin.cash_credit_rider");//not_using
    /*[accounts - receive cash from rider]*/Route::post("/accounts/rider/cash/debit","RiderDetailController@cash_debit_rider")->name("admin.cash_debit_rider");
    /*[accounts - Mics Charges]*/Route::post("/accounts/rider/mics/charges","RiderDetailController@mics_charges")->name("admin.mics_charges");

    /*[accounts - send to profit]*/Route::post("/accounts/company/profit/add","AccountsController@add_company_profit")->name("admin.accounts.add_company_profit");
    /*[company - overall reports]*/Route::get("/company/overall/report","AccountsController@company_overall_report")->name("admin.accounts.company_overall_report");
    /*[accounts - pay bills]*/Route::put('/bill/payment/{rider_id}/updateStatus/{month}/{type}','AccountsController@updateBillPaymentStatus')->name('admin.updateBillPaymentStatus');

    // investment 
    /*[company - add investment]*/Route::get("/kr_investment/add","AccountsController@kr_investment_index")->name("admin.accounts.kr_investment_index");
    /*[company - add investment]*/Route::post("/kr_investment/add-data","AccountsController@kr_investment_post")->name("admin.accounts.kr_investment_post");
    /*[company - view all investments]*/Route::get("/kr_investment/view","AccountsController@kr_investment_view")->name("admin.accounts.kr_investment_view");
    /*[company - active/inactive investment]*/Route::POST('/kr_investment/{kr_investment}/updatestatus','AccountsController@updateStatusKr_investment')->name('admin.accounts.updateStatusKr_investment');
    /*[company - delete investment]*/Route::delete('/kr_investment/{kr_investment}', 'AccountsController@delete_kr_investment')->name('admin.accounts.delete_kr_investment');
    /*[company - edit investment]*/Route::get('/kr_investment/edit/{shop_id}','AccountsController@kr_investment_edit')->name('admin.kr_investment_edit');
    /*[company - view investment]*/Route::get('/kr_investment/edit/view/{shop_id}','AccountsController@kr_investment_edit_view')->name('admin.kr_investment_edit_view');
    /*[company - edit investment]*/Route::post('/kr_investment/update/{shop_id}','AccountsController@kr_investment_update')->name('admin.kr_investment_update');
    //end investment
    Route::get('/salary/slip/for/riders','AccountsController@salary_slip')->name('account.salary_slip');//not_using
    /*[accounts - pay salary]*/Route::post("/accounts/rider/remaining_salary/add","AccountsController@rider_remaining_salary_add")->name("admin.accounts.rider_remaining_salary_add");

// end Accounts

// Expense

    Route::get("/kr-bikes/kr-account","KRController@account_view")->name("admin.KR_Bikes.account_view");//not_using

    /*[employee - view accounts]*/Route::get("/employee/salary_generate","EmployeeController@salary_generated")->name("employee.salary_generated");
    /*[employee - add bonus]*/Route::get("/employee/bonus","EmployeeController@employee_bonus");
    /*[employee - add fine]*/Route::get("/employee/fine","EmployeeController@employee_fine");
// Expense  

// fuel_expense
    /*[Accounts - add fuel]*/Route::get('/fuel_expense/create','AccountsController@fuel_expense_create')->name('admin.fuel_expense_create');
    /*[Accounts - add fuel]*/Route::post('/accounts/fuel_expense/insert','AccountsController@fuel_expense_insert')->name('admin.fuel_expense_insert');
    /*[Accounts - view all fuels]*/Route::get('/accounts/fuel_expense/view','AccountsController@fuel_expense_view')->name('admin.fuel_expense_view');
    Route::get('/accounts/fuel/expense/select/riders/bike/{rider_id}/{bike_id}','AccountsController@fuel_rider_selector')->name('fuel.fuel_rider_selector');//not_using
    /*[Accounts - delete fuel]*/Route::delete('/accounts/fuel_expense/delete/{expense_id}','AccountsController@delete_fuel_expense')->name('admin.delete_fuel_expense');
    /*[Accounts - active/inactive fuel]*/Route::post('/accounts/fuel_expense/{expense_id}/updatestatus','AccountsController@update_fuel_expense')->name('admin.update_fuel_expense');
    /*[Accounts - edit fuel]*/Route::get('/accounts/fuel_expense/edit/{expense_id}','AccountsController@edit_fuel_expense')->name('admin.edit_fuel_expense');
    /*[Accounts - edit fuel]*/Route::post('/accounts/fuel_expense/update/{expense_id}','AccountsController@update_edit_fuel_expense')->name('admin.update_edit_fuel_expense');
    /*[Accounts - view fuel]*/Route::get('/accounts/fuel_expense/edit/view/{expense_id}','AccountsController@edit_fuel_expense_view')->name('admin.edit_fuel_expense_view');
// end fuel_expense
// id-charges
    /*[Accounts - add Id charge]*/Route::get("/accounts/id-charges","AccountsController@id_charges_index")->name("admin.accounts.id_charges_index");
    /*[Accounts - add Id charge]*/Route::post("/accounts/id-charges/add","AccountsController@id_charges_post")->name("admin.accounts.id_charges_post");
    /*[Accounts - view all Id charge]*/Route::get("/accounts/id-charges/view","AccountsController@id_charges_view")->name("admin.accounts.id_charges_view");
    /*[Accounts - active/inactive Id charge]*/Route::put('/accounts/id-charges/{charge}/updateStatus','AccountsController@updateStatusIdCharges')->name('admin.accounts.updateStatusIdCharges');
    /*[Accounts - delete Id charge]*/Route::delete('/accounts/id-charges/{id_charges_id}', 'AccountsController@delete_id_charges')->name('admin.accounts.delete_id_charges');
    /*[Accounts - edit Id charge]*/Route::get('/accounts/id-charges/edit/{charge_id}','AccountsController@id_charges_edit')->name('admin.id_charges_edit');
    /*[Accounts - edit Id charge]*/Route::post('/accounts/id-charges/update/{charge_id}','AccountsController@id_charges_update')->name('admin.id_charges_update');
    /*[Accounts - view Id charge]*/Route::get('/accounts/id-charges/edit/view/{charge_id}','AccountsController@id_charges_edit_view')->name('admin.id_charges_edit_view');
// end id-charges
// workshop
    /*[Accounts - add Workshop]*/Route::get("/accounts/workshop/add","AccountsController@workshop_index")->name("admin.accounts.workshop_index");
    /*[Accounts - add Workshop]*/Route::post("/accounts/workshop/add-data","AccountsController@workshop_post")->name("admin.accounts.workshop_post");
    /*[Accounts - view all Workshop]*/Route::get("/accounts/workshop/view","AccountsController@workshop_view")->name("admin.accounts.workshop_view");
    /*[Accounts - active/inactive Workshop]*/Route::put('/accounts/workshop/{workshop}/updateStatus','AccountsController@updateStatusWorkshop')->name('admin.accounts.updateStatusWorkshop');
    /*[Accounts - delete Workshop]*/Route::delete('/accounts/workshop/{workshop_id}', 'AccountsController@delete_workshop')->name('admin.accounts.delete_workshop');
    /*[Accounts - edit Workshop]*/Route::get('/accounts/workshop/edit/{shop_id}','AccountsController@workshop_edit')->name('admin.workshop_edit');
    /*[Accounts - edit Workshop]*/Route::post('/accounts/workshop/update/{shop_id}','AccountsController@workshop_update')->name('admin.workshop_update');
    /*[Accounts - view Workshop]*/Route::get('/accounts/workshop/edit/view/{shop_id}','AccountsController@workshop_edit_view')->name('admin.workshop_edit_view');
// end workshop
//edirham
    /*[Accounts - add Edirham]*/Route::get("/accounts/edirham/add","AccountsController@edirham_index")->name("admin.accounts.edirham_index");
    /*[Accounts - add Edirham]*/Route::post("/accounts/edirham/add-data","AccountsController@edirham_post")->name("admin.accounts.edirham_post");
    /*[Accounts - view all Edirham]*/Route::get("/accounts/edirham/view","AccountsController@edirham_view")->name("admin.accounts.edirham_view");
    /*[Accounts - active/inactive Edirham]*/Route::put('/accounts/edirham/{edirham}/updateStatus','AccountsController@updateStatusEdirham')->name('admin.accounts.updateStatusEdirham');
    /*[Accounts - delete Edirham]*/Route::delete('/accounts/edirham/{edirham}', 'AccountsController@delete_edirham')->name('admin.accounts.delete_edirham');
    /*[Accounts - edit Edirham]*/Route::get('/accounts/edirham/edit/{id}','AccountsController@edirham_edit')->name('admin.edirham_edit');
    /*[Accounts - edit Edirham]*/Route::post('/accounts/edirham/update/{id}','AccountsController@edirham_update')->name('admin.edirham_update');
    /*[Accounts - view Edirham]*/Route::get('/accounts/edirham/edit/view/{id}','AccountsController@edirham_edit_view')->name('admin.edirham_edit_view');
//end edirham
// maintenance
    /*[Accounts - add Maintenance]*/Route::get("/accounts/maintenance/add","AccountsController@maintenance_index")->name("admin.accounts.maintenance_index");
    /*[Accounts - add Maintenance]*/Route::post("/accounts/maintenance/add-data","AccountsController@maintenance_post")->name("admin.accounts.maintenance_post");
    /*[Accounts - view all Maintenance]*/Route::get("/accounts/maintenance/view","AccountsController@maintenance_view")->name("admin.accounts.maintenance_view");
    /*[Accounts - active/inactive Maintenance]*/Route::put('/accounts/maintenance/{maintenance}/updateStatus','AccountsController@updateStatusMaintenance')->name('admin.accounts.updateStatusMaintenance');
    /*[Accounts - delete Maintenance]*/Route::delete('/accounts/maintenance/{maintenance}', 'AccountsController@delete_maintenance')->name('admin.accounts.delete_maintenance');
    /*[Accounts - edit Maintenance]*/Route::get('/accounts/maintenance/edit/{shop_id}','AccountsController@maintenance_edit')->name('admin.maintenance_edit');
    /*[Accounts - edit Maintenance]*/Route::get('/accounts/maintenance/edit/view/{shop_id}','AccountsController@maintenance_edit_view')->name('admin.maintenance_edit_view');
    /*[Accounts - view Maintenance]*/Route::post('/accounts/maintenance/update/{shop_id}','AccountsController@maintenance_update')->name('admin.maintenance_update');
//end maintenance
//Company_Expense
    /*[Accounts - add Company Expense]*/Route::get('/accounts/CE/index','ExpenseController@CE_index')->name('admin.CE_index');
    /*[Accounts - view all Company Expense]*/Route::get('/accounts/CE/view','ExpenseController@CE_view')->name('admin.CE_view');
    /*[Accounts - add Company Expense]*/Route::post('/accounts/CE/insert','ExpenseController@CE_store')->name('admin.CE_store');
    /*[Accounts - edit Company Expense]*/Route::post('/accounts/CE/{id}/update','ExpenseController@CE_update')->name('admin.CE_update');
    /*[Accounts - Active or Inactive Company Expense]*/Route::post('/accounts/CE/{id}/updatestatus','ExpenseController@CE_updatestatus')->name('admin.CE_updatestatus');
    /*[Accounts - Delete Company Expense]*/Route::delete('/accounts/CE/delete/{id}','ExpenseController@CE_delete')->name('admin.CE_delete');
    /*[Accounts - Edit Company Expense]*/Route::get('/accounts/CE/edit/{id}','ExpenseController@CE_edit')->name('admin.CE_edit');
    /*[Accounts - view Company Expense report]*/Route::get('/accounts/CE/report','ExpenseController@CE_report')->name('admin.CE_report');
    /*[Accounts - View Company Expense]*/Route::get('/accounts/CE/edit/view/{id}','ExpenseController@CE_edit_view')->name('admin.CE_edit_view');
// End Company_Expense
//     WPS
    /*[Accounts - add WPS]*/Route::get('/accounts/wps/index','ExpenseController@wps_index')->name('admin.wps_index');
    /*[Accounts - view all WPS]*/Route::get('/accounts/wps/view','ExpenseController@wps_view')->name('admin.wps_view');
    /*[Accounts - add WPS]*/Route::post('/accounts/wps/insert','ExpenseController@wps_store')->name('admin.wps_store');
    /*[Accounts - edit WPS]*/Route::post('/accounts/wps/{id}/update','ExpenseController@wps_update')->name('admin.wps_update');
    /*[Accounts - active/inactive WPS]*/Route::post('/accounts/wps/{id}/updatestatus','ExpenseController@wps_updatestatus')->name('admin.wps_updatestatus');
    /*[Accounts - delete WPS]*/Route::delete('/accounts/wps/delete/{id}','ExpenseController@wps_delete')->name('admin.wps_delete');
    /*[Accounts - edit WPS]*/Route::get('/accounts/wps/edit/{id}','ExpenseController@wps_edit')->name('admin.wps_edit');
    /*[Accounts - view WPS]*/Route::get('/accounts/wps/edit/view/{id}','ExpenseController@wps_edit_view')->name('admin.wps_edit_view');
// End WPS
//ADVANCE & RETURN
    /*[Accounts - add Advance]*/Route::get('/accounts/AR/index','ExpenseController@AR_index')->name('admin.AR_index');
    /*[Accounts - view all Advance]*/Route::get('/accounts/AR/view','ExpenseController@AR_view')->name('admin.AR_view');
    /*[Accounts - add Advance]*/Route::post('/accounts/AR/insert','ExpenseController@AR_store')->name('admin.AR_store');
    /*[Accounts - edit Advance]*/Route::post('/accounts/AR/{id}/update','ExpenseController@AR_update')->name('admin.AR_update');
    /*[Accounts - active/inactive Advance]*/Route::post('/accounts/AR/{id}/updatestatus','ExpenseController@AR_updatestatus')->name('admin.AR_updatestatus');
    /*[Accounts - delete Advance]*/Route::delete('/accounts/AR/delete/{id}','ExpenseController@AR_delete')->name('admin.AR_delete');
    /*[Accounts - edit Advance]*/Route::get('/accounts/AR/edit/{id}','ExpenseController@AR_edit')->name('admin.AR_edit');
    /*[Accounts - view Advance]*/Route::get('/accounts/AR/edit/view/{id}','ExpenseController@AR_edit_view')->name('admin.AR_edit_view');
// End ADVANCE & RETURN
//Bike Fine
    /*[Accounts - add Bike Fine]*/Route::get('/accounts/BF/index','KRController@BF_index')->name('admin.BF_index');
    /*[Accounts - view all Bike Fine]*/Route::get('/accounts/BF/view','KRController@BF_view')->name('admin.BF_view');
    /*[Accounts - add Bike Fine]*/Route::post('/accounts/BF/insert','KRController@BF_store')->name('admin.BF_store');
    /*[Accounts - edit Bike Fine]*/Route::post('/accounts/BF/{id}/update','KRController@BF_update')->name('admin.BF_update');
    /*[Accounts - delete Bike Fine]*/Route::delete('/accounts/BF/delete/{id}','KRController@BF_delete')->name('admin.BF_delete');
    /*[Accounts - edit Bike Fine]*/Route::get('/accounts/BF/edit/{id}','KRController@BF_edit')->name('admin.BF_edit');
    /*[Accounts - view Bike Fine]*/Route::get('/accounts/BF/edit/view/{id}','KRController@BF_edit_view')->name('admin.BF_edit_view');
    /*[Accounts - Pay Bike Fine]*/Route::get('/accounts/fine/paid/Rider/{rider_id}/{bike_fine_id}/{amount}/{month}','KRController@paid_fine_by_rider')->name('admin.BF_pay');
// End Bike Fine
    Route::get("/accounts/accounts/testing","HomeController@accounts_testing_v1")->name("admin.accounts.testing_v1");//not_using
   
// end Expense

// NewComer
						   
			  
    // accounts
    
    Route::get('/newComer/add','NewComerController@new_comer_form')->name('NewComer.form'); //ok [New comer: add new comer]
    Route::post('/newComer/insert','NewComerController@insert_newcomer')->name('NewComer.insert'); //ok [New comer: add new comer]
    Route::get('/newComer/view','NewComerController@new_comer_view')->name('NewComer.view'); //ok [New comer: view new comer's]
    Route::delete('/newComer/delete/{newComer_id}','NewComerController@delete_new_comer')->name('NewCome.delete'); //ok [New comer: delete new comer]
    Route::get('/newComer/Edit/{id}','NewComerController@newComer_edit')->name('NewComer.edit'); //ok [New comer: update new comer]
    Route::get('/newComer/Edit/view/{id}','NewComerController@newComer_edit_view')->name('NewComer.edit_view'); //ok [New comer: update new comer]
    Route::post('/newComer/{id}/update', 'NewComerController@updateNewComer')->name('NewComer.updatenewComer'); //ok [New comer: update new comer]
    Route::get('/newComer/popup/{newComer_id}','NewComerController@newComer_popup')->name('NewComer.popup'); //not in use
    Route::get('/newComer/approval','NewComerController@new_comer_approval_view')->name('NewComer.approval'); //ok [New comer: new approval view]
    Route::post('/newComer/approved','NewComerController@new_comer_approved')->name('NewComer.approved'); ///not in use
    Route::post('/newComer/add_interview_status','NewComerController@add_interview_status')->name('NewComer.add_interview_status'); //not in use
    Route::post('/newComer/add_interview_date','NewComerController@add_interview_date')->name('NewComer.add_interview_date'); //not in use


// End NewComer
// Activity
    /*[Logs Activity - view]*/Route::get('/activity/view','KRController@activity_view')->name('admin.activity.view');
    /*[Logs Activity - delete log]*/Route::delete('/delete/activity/{id}','KRController@delete_activity_log')->name('admin.delete_activity_log');
    Route::get('/tax/KR','KRController@gov_tax')->name('admin.gov_tax');//not_using

// End Activity
// Sim		  
    // accounts

 //    Start Sim Section    
			
    Route::get('/create/Sim','SimController@add_sim')->name('Sim.new_sim'); ///ok [Sim: add sim]
    Route::post('/store/Sim','SimController@store_sim')->name('Sim.store_sim'); ///ok [Sim: add sim]
    Route::get('/view/records/Sim','SimController@view_records_sim')->name('Sim.view_records'); ///ok [Sim: view all sim]
    Route::get('/view/records/Sim/active','SimController@view_records_sim_active')->name('Sim.view_records_sim_active'); //ok [Sim: view active sim's]
    Route::get('/edit/{id}/Sim','SimController@edit_sim')->name('Sim.edit_sim'); ///ok [Sim: update sim record]
    Route::get('/edit/Sim/view/{id}','SimController@edit_sim_view')->name('Sim.edit_sim_view'); ///ok [Sim: view sim]
    Route::post('/update/{id}/Sim','SimController@update_sim')->name('Sim.update_sim');   ///ok [Sim: update sim record]
    Route::post('/sim/{sim_id}/updateStatus','SimController@updateStatusSim')->name('Sim.updateStatus_sim');  ///ok [Sim: update sim record]
    Route::delete('/sim/{sim_id}', 'SimController@DeleteSim')->name('Sim.DeleteSim'); ///ok [Sim: Delete sim]
    Route::get('/sim/history/rider/{sim_id}','SimController@getRiderHistory')->name('Sim.rider.history'); ///ok [Sim: history]
// End Sim Section  
// Start Sim Transaction Section
    Route::get('/create/Transaction/Sim','SimController@add_simTransaction')->name('SimTransaction.create_sim');  //ok [Sim: create transactions]
    Route::post('/store/Transaction/Sim','SimController@store_simTransaction')->name('SimTransaction.store_simTransaction'); //ok [Sim: create transactions]
    Route::get('/view/Transaction/Sim','SimController@view_sim_transaction_records')->name('SimTransaction.view_records'); //ok [Sim: view transactions]
    Route::get('edit/Transaction/{id}/Sim','SimController@edit_simTransaction')->name('SimTransaction.edit_sim'); //ok [Sim: update transactions]
    Route::post('sim_transaction/inline_edit','SimController@edit_inline_simTransaction')->name('SimTransaction.edit_sim_inline'); //ok [Sim: update transactions]
    Route::post('/update/Transaction/{id}/Sim','SimController@update_simTransaction')->name('SimTransaction.update'); //ok [Sim: update transactions]
    Route::post('/simTransaction/{id}/updateStatus','SimController@updateStatusSimTransaction')->name('SimTransaction.updateStatus');  //ok [Sim: update transactions]
    Route::delete('/simTransaction/{sim_id}', 'SimController@DeleteSimTransaction')->name('SimTransaction.DeleteSim');  //ok [Sim: delete transactions]
// End Sim Transaction Section
// start Sim history section --------
    Route::get('/create/history/Sim/{rider_id}','SimController@add_simHistory')->name('SimHistory.addsim'); //ok [Rider: assign sim history]
    Route::post('/store/history/Sim/{rider_id}','SimController@store_simHistory')->name('SimHistory.store_simHistory'); //ok [Rider: assign sim history]
    Route::post('/update/History/{id}/Sim','SimController@update_simHistory')->name('SimHistory.update'); //ok [Rider: change sim data]
    Route::get('/view/Sim/{id}','SimController@view_assigned_sim')->name('Sim.view_assigned'); //not using
    Route::delete('/sim/{rider_id}/removeSim/{sim_id}', 'SimController@removeSim')->name('Sim.removeSim');  //ok [Sim: view sim history]
    Route::get('/view/{rider_id}/simHistory','SimController@sim_History')->name('Sim.simHistory');  //ok [Rider: view sim history]
    Route::get('/change/sim/{rider_id}/history/{assign_sim_id}','SimController@sim_dates_History')->name('Sim.sim_dates_History'); //ok[Rider: change sim date history]
    Route::get('/sim/deactive/{rider_id}/date/{sim_id}','SimController@sim_deactive_date')->name('admin.sim_deactive_date'); //ok [Rider: unassign sim]
    Route::get('/sim/allowed/balance/{rider_id}/update/{sim_id}','SimController@update_allowed_abalance')->name('Sim.update_allowed_abalance'); //ok [Rider: update allow balance]
// end Sim history section 

// End Sim

// mobile 

 //Mobiles   
    Route::get('/add/purchased_invoices','MobileController@add_purchased_invoices')->name('mobile.add_purchased_invoices');
    Route::post('/insert/purchased_invoices','MobileController@submit_purchased_invoices')->name('mobile.submit_purchased_invoices');
    
    Route::get('/mobile/create','MobileController@create_mobile_GET')->name('mobile.create_mobile_GET');
    Route::post('/mobile/create/add','MobileController@create_mobile_POST')->name('mobile.create_mobile_POST');
    Route::get('/mobiles','MobileController@mobiles')->name('mobile.show');
    Route::get('/mobile/{mobile}/edit','MobileController@update_mobile_GET')->name('mobile.edit');
    Route::get('/mobile/invoices/profile/{mobile_id}','MobileController@Mobile_view_ivoice_profile')->name('Mobile.Mobile_view_ivoice_profile');
    Route::post('/mobile/{id}/update','MobileController@update_mobile')->name('Mobile.update');
    Route::post('/add/seller/details/','MobileController@addSellerDeatil')->name("Mobile.addSellerDeatil");
    Route::get('/mobile/installment/create','MobileController@create_mobileInstallment')->name('MobileInstallment.create');
    Route::post('/mobile/installment/insert','MobileController@store_mobileInstallment')->name('MobileInstallment.store');
    Route::get('/mobile/assign_to_rider/{rider_id}',"MobileController@mobile_assign_to_rider")->name('Mobile.mobile_assign_to_rider');
    Route::post('/mobile/assignedMobile_to_rider/{rider_id}','MobileController@mobile_is_assigned_to_rider')->name('Mobile.mobile_is_assigned_to_rider');
    Route::get('/mobile/rider_history/{rider_id}','MobileController@mobile_rider_history')->name('Mobile.mobile_rider_history');
    Route::get('/mobile/change_given_date/history/{rider_id}/{mobile_history_id}','MobileController@change_Mobile_given_date')->name('Mobile.change_Mobile_given_date');
    Route::get('/mobile/ajax/data/{mobile_id}/{month}','MobileController@consumption_mobile_records')->name('Mobile.consumption_mobile_records');
    Route::get('/mobile/sellers/view','MobileController@sellers_view')->name('mobile.sellers_view');
    Route::get('/mobile/sellers/edit/{seller_id}','MobileController@sellers_edit')->name('mobile.sellers_edit');
    Route::post('/mobile/sellers/update/{seller_id}','MobileController@sellers_update')->name('mobile.sellers_update');
    Route::get('/mobile/accessory/view','MobileController@accessory_view')->name('mobile.accessory_view');
    Route::get('/mobile/accessory/edit/{accessory_id}','MobileController@accessory_edit')->name('mobile.accessory_edit');
    Route::post('/mobile/accessory/update/{accessory_id}','MobileController@accessory_update')->name('mobile.accessory_update');

// end mobile 
			 		  
//zomato income
    Route::get("/assign/client/rider_id/{p_id}/{feid}/{rider_id}","AccountsController@assign_client_rider_id")->name("income.assign_client_rider_id"); //ok - update FEID (onclick) when no feid is found on income zomato table 
    Route::get("/Salary/accounts/income/zomato/index","AccountsController@income_zomato_index")->name("admin.accounts.income_zomato_index"); //ok [Client: view zomato income]
    Route::post('/accounts/income/zomato/import','AccountsController@income_zomato_import')->name('admin.accounts.income_zomato_import'); //ok [Client: zomato import income]
//zomato income 
// Client_income
    Route::get('/client_income/index','AccountsController@client_income_index')->name('admin.client_income_index'); //ok [Income: add client income]
    Route::get('/client_income/{client_id}/getRiders','AccountsController@client_income_getRiders')->name('admin.client_income_getRiders'); //ok [Income: add client income]
    Route::get('/client_income/view','AccountsController@client_income_view')->name('admin.client_income_view'); //ok [Income: view all clients income]
    Route::post('/client_income/insert','AccountsController@client_income_store')->name('admin.client_income_store'); //ok [Income: add client income]
    Route::post('/client_income/{id}/update','AccountsController@client_income_update')->name('admin.client_income_update');  //ok [Income: update client income]
    Route::post('/client_income/{id}/updatestatus','AccountsController@client_income_updatestatus')->name('admin.client_income_updatestatus'); //ok [Income: update status client income]
    Route::delete('/client_income/delete/{id}','AccountsController@client_income_delete')->name('admin.client_income_delete'); //ok [Income: delete client income]
    Route::get('/client_income/edit/{id}','AccountsController@client_income_edit')->name('admin.client_income_edit');  //ok [Income: update client income]
    Route::get('/client_income/edit/view/{id}','AccountsController@client_income_edit_view')->name('admin.client_income_edit_view'); //ok [Income: view client income]
// end Client_income 

// company profit
    Route::get('/client/profit/sheet/{client_id}','ClientController@profit_client')->name('client.profit_sheet_view'); //ok [Client: profit sheet]
    Route::get('/client/total/expense/sheet/{client_id}','RiderDetailController@client_total_expense')->name('client.client_total_expense'); //ok [Client: view summary]
    Route::get('/client/month/record/{month}/{client}','RiderDetailController@summary_month')->name('client.summary_month'); //ok [Client: view summary per month]
	 
    Route::get('/generated/month/bills','BillsController@rider_generated_bills')->name('bills.rider_generated_bills'); //ok [Accounts: bill details]
// end company profit
    //admin routes only
   Route::get('/add/invoice/tax','InvoiceController@add_invoice')->name('tax.add_invoice');  //ok [Invoice: add invoice]
   Route::POST('/add/invoice','InvoiceController@add_invoice_post')->name('tax.add_invoice_post'); //ok [Invoice: add invoice]
   Route::get('/invoice/tax/ajax/get_clients_details/{client_id}/{month}','InvoiceController@get_ajax_client_details')->name('tax.get_ajax_client_details'); //ok [Invoice: add invoice]
   Route::get('/invoice/get_invoice_by_id/{invoice_id}','InvoiceController@get_invoice_by_id')->name('invoice.get_invoice_by_id'); //ok [Invoice:add invoice]***
   Route::get('/invoice/view','InvoiceController@view_invoices')->name('tax.view_invoices'); //ok [Invoice: view invoices]
   Route::get('/invoice/tax_method/add','InvoiceController@add_tax_method')->name('invoice.add_tax_method');//ok [Invoice: add view detail]
   Route::post('/invoice/tax_method/store','InvoiceController@store_tax_method')->name('invoice.store_tax_method'); //ok [Invoice: save tax detail]
   Route::get('/invoice/bank_account/add','InvoiceController@add_bank_account')->name('invoice.add_bank_account'); //ok [Invoice: view bank detail]
   Route::post('/invoice/bank_account/store','InvoiceController@store_bank_account')->name('invoice.store_bank_account'); //ok [Invoice: save bank detail]
   Route::get('/invoice/get/open/{client_id}','InvoiceController@getOpenIvoices')->name('invoice.getOpenIvoices'); //ok [Invoice:save invoice payment]
   Route::post('/invoice/payment/save','InvoiceController@save_payment')->name('invoice.save_payment'); //ok [Invoice:save invoice payment]
   Route::get('/invoice/payments/view','InvoiceController@invoive_payments')->name('invoice.invoive_payments'); //ok [Invoice:view payments]
   Route::get('/companyinfo','InvoiceController@company_info')->name('invoice.company_info');   //ok  [Company: show company info]
   Route::post('/companyinfo/store','InvoiceController@company_info_store')->name('invoice.company_info_store'); //ok  [Company: update company info]
  
   Route::post('/view/upload/salary_slip/{month}/{rider_id}','RiderDetailController@view_upload_salary_slip')->name('rider.view_upload_salary_slip');//[Accounts-Upload salary Slip] .

   Route::put('accounts/company/edit', 'AccountsController@edit_company_account')->name('admin.accounts.edit_company'); //[Accounts - Edit company account]
   Route::put('accounts/rider/edit', 'AccountsController@edit_rider_account')->name('admin.accounts.edit_rider'); //[Accounts - Edit rider account]
   Route::get('/sim/bill/image/{rider_id}/{month}/{type}','SimController@SimBIllImage')->name('Sim.SimBIllImage');//[Accounts-Sim Bill Image] .
//end admin routs only


//routes not added on access page
Route::get('/add_routes','HomeController@show_add_routes')->name('admin.add_routes'); //ok [for developer]
Route::post('/insert_routes','HomeController@insert_add_routes')->name('admin.insert_routes'); //ok [for developer]
Route::get('/view_routes','HomeController@view_add_routes')->name('admin.view_routes'); //ok [for developer]
Route::post('{route}/edit_route','HomeController@update_add_routes')->name('admin.update_route'); //ok [for developer]
Route::get('{route}/edit_route','HomeController@edit_routes')->name('admin.edit_route'); //ok [for developer]
Route::get('/add/employee','Auth\EmployeeController@showloginform')->name('Employee.showloginform');   ///only for admin
Route::post('/insert/employee','Auth\EmployeeController@insert_employee')->name('Employee.insert_employee'); ///only for admin
Route::get('/show/employee','Auth\EmployeeController@viewEmployee')->name('Employee.viewEmployee'); ///only for admin
Route::get('/show/employee/ajax','Auth\EmployeeController@getEmployee')->name('Employee.getEmployee'); ///only for admin
Route::delete('/delete/employee/{employee_id}','Auth\EmployeeController@deleteEmployee')->name('Employee.deleteEmployee'); ///only for admin
Route::get('/edit/employee/{employee_id}','Auth\EmployeeController@edit_employee')->name('Employee.edit_employee'); ///only for admin
Route::get('/view/employee/{employee_id}','Auth\EmployeeController@view_employee')->name('Employee.view_employee'); ///only for admin
Route::post('/update/employee/{employee_id}','Auth\EmployeeController@update_employee')->name('Employee.update_employee'); ///only for admin
});
// end for Admin

// for Admin global
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
										 
], function(){
    Route::get('/profile', 'HomeController@profile')->name('admin.profile');   ///ok [Admin:view profile]
    Route::put('/profile', 'HomeController@updateProfile')->name('admin.profile.update');  ///ok [Admin:update profile]
    Route::get('/403','HomeController@request403')->name('request.403'); ///ok
});
																				 
   
// end for Admin global

///for guest customers
Route::group([
    'prefix' => 'guest',
], function(){
// Guest routes
    Route::get('/newcomer/add','GuestController@newComer_view')->name('guest.newComer_view');   //ok
    Route::post('/newcomer/store','GuestController@newComer_add')->name('guest.newComer_add');  //ok
    Route::post('/newcomer/status_check','GuestController@newComer_status')->name('guest.newComer_status');  //ok
    // end Guest routes
});
 ///end for guest