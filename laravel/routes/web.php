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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', 'UserController@showLog')->name('home');
Route::get('/main', 'UserController@showLog')->name('main');
Route::post('/main', 'UserController@logSearch')->name('logSearch');


Route::prefix('admin')->group(function () {
    Route::get('/', 'Admin\HomeController@index')->name('admin.home');

    Route::get('login', 'Admin\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Admin\Auth\LoginController@login')->name('admin.login');
    Route::get('logout', 'Admin\Auth\LoginController@logout')->name('admin.logout');
    Route::post('logout', 'Admin\Auth\LoginController@logout')->name('admin.logout');
    
    Route::get('register', 'Admin\Auth\RegisterController@showRegisterForm')->name('admin.register');
    Route::post('register', 'Admin\Auth\RegisterController@register')->name('admin.register');
    
    Route::get('admin_list', 'Admin\HomeController@adminList')->name('admin.adminList');
    Route::delete('admin_list/{admin}', 'Admin\HomeController@adminDelete')->name('admin.adminDelete'); 

    Route::get('user_list/{ascOrDesc}', 'Admin\UserController@index')->name('admin.userList');
    Route::delete('user_list/{user}', 'Admin\UserController@userDelete')->name('admin.userDelete'); 

    Route::get('user_edit/{user}', 'Admin\UserController@userEdit')->name('admin.userEdit');
    Route::patch('user_edit/{user}', 'Admin\UserController@userEditExecute')->name('admin.userEditExecute');
    Route::post('user_fund_reset/{user}', 'Admin\UserController@userFundReset')->name('admin.userFundReset');

    Route::get('add_fund/{user}', 'Admin\UserController@addFund')->name('admin.addFund');
    Route::patch('add_fund/{user}', 'Admin\UserController@addFundCheck')->name('admin.addFundCheck');

    Route::get('user_prof/{user}', 'Admin\UserController@userProf')->name('admin.userProf');

    Route::get('user_searchView', 'Admin\UserController@userSearchView')->name('admin.userSearchView');
    Route::get('user_search', 'Admin\UserController@userSearch')->name('admin.userSearch');

    Route::match(['get', 'post'], 'user_bulk_edit', 'Admin\UserController@userBulkEdit')->name('admin.userBulkEdit');
    Route::post('user_bulk_edit_done', 'Admin\UserController@userBulkEditCheck')->name('admin.userBulkEditCheck');
    Route::patch('user_bulk_edit_done', 'Admin\UserController@userBulkEditDone')->name('admin.userBulkEditDone');
    Route::delete('user_bulk_edit_done', 'Admin\UserController@userBulkDelete')->name('admin.userBulkDelete');


    Route::get('base_schedule_list/{ascOrDesc}', 'Admin\ScheduleController@baseScheduleList')->name('admin.baseScheduleList');
    Route::get('add_base_schedule', 'Admin\ScheduleController@addBaseSchedule')->name('admin.addBaseSchedule');

    Route::get('add_schedule_conf', 'Admin\ScheduleController@addBaseSchedule')->name('admin.addScheduleRedirect');
    Route::post('add_schedule_conf', 'Admin\ScheduleController@addScheduleConf')->name('admin.addScheduleConf');

    Route::post('add_schedule_execute', 'Admin\ScheduleController@addScheduleExecute')->name('admin.addScheduleExecute');

    Route::get('custom_schedule_list/{ascOrDesc}', 'Admin\ScheduleController@customScheduleList')->name('admin.customScheduleList');
    Route::get('add_custom_schedule/{user}', 'Admin\ScheduleController@addCustomSchedule')->name('admin.addCustomSchedule');

    Route::delete('schedule_delete', 'Admin\ScheduleController@scheduleDelete')->name('admin.scheduleDelete'); 
    Route::delete('schedule_array_delete', 'Admin\ScheduleController@scheduleArrayDelete')->name('admin.scheduleArrayDelete'); 

    Route::get('config_setting', 'Admin\AppConfigController@settingView')->name('admin.configSettingView');
    Route::post('config_setting', 'Admin\AppConfigController@setting')->name('admin.configSetting');
    Route::post('role_setting', 'Admin\AppConfigController@roleSetting')->name('admin.roleSetting');

    Route::get('show_fund_log/{user}', 'Admin\FundLogController@showLog')->name('admin.showLog');
    Route::post('show_fund_log', 'Admin\FundLogController@logSearch')->name('admin.logSearch');

    Route::get('show_fund_future_log/{user}', 'Admin\FundLogController@showFutureLog')->name('admin.showFutureLog');
    Route::post('get_future_Log/{user}', 'Admin\FundLogController@getFutureLog')->name('admin.getFutureLog');

    Route::get('show_charge_log/{user}', 'Admin\UserController@showChargeLog')->name('admin.showChargeLog');
});
