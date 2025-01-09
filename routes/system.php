<?php

Route::get('/logout', 'Auth\LoginController@logout')->name('logout'); //
Route::post('/verifiy-code', 'Auth\LoginController@verifiyCode')->name('system.code-post');

Auth::routes();

Route::get('/git-branches','GitActionsController@index')->name('system.git-branches');
Route::post('/checkout-branch/{branch_name}','GitActionsController@checkout_branch')->name('system.checkout-branch');
Route::get('/change','GitActionsController@change')->name('system.change');


Route::get('/user/change-password', 'UserController@changePassword')->name('system.user.change-password');

Route::post('/user/change-password', 'UserController@changePasswordPost')->name('system.user.change-password-post');

Route::get('/user/profile-update', 'UserController@editProfile')->name('system.user.profile');
Route::get('/user/show-profile', 'UserController@showProfile')->name('system.user.show-profile');

Route::patch('/user/update-profile', 'UserController@updateProfile')->name('system.user.update-profile');


Route::resource('/user', 'UserController', ['as' => 'system']); //

Route::get('/user/get-activity-log/{id}', 'UserController@getUserActivityLog')->name('system.get-user-activity-log'); //
Route::get('/user/get-auth-session/{id}', 'UserController@getAuthSession')->name('system.get-auth-session'); //

Route::resource('/customer', 'CustomerController', ['as' => 'system']); //

Route::get('/ajax', 'AjaxController@index')->name('system.misc.ajax'); //

Route::resource('/permission-group', 'PermissionGroupsController', ['as' => 'system']); //

Route::get('/', 'Dashboard@index')->name('system.dashboard');
Route::get('/user-sessions', 'AuthSessionController@authSessionForUser')->name('system.user.user-sessions');
Route::resource('/auth-sessions', 'AuthSessionController', ['as' => 'system']); //
// Activity LOG
Route::get( '/activity-log/{ID}', 'ActivityController@show' )->name( 'system.activity-log.show' ); //
Route::get( '/activity-log', 'ActivityController@index' )->name( 'system.activity-log.index' ); //


Route::resource('/departments', 'DepartmentController', ['as' => 'system']);
Route::resource('/items', 'DepartmentController', ['as' => 'system']);
Route::get('/location-quantities', 'ItemController@location_quantities')->name('system.location-quantities');
Route::resource('/order', 'OrderController', ['as' => 'system']);
Route::post('order-assign','OrderController@assign_picker')->name('system.order.assign');
Route::get('order-picking-packing','OrderFulfillmentController@index')->name('system.order.picking-packing');
Route::get('order-ready-to-ship','ReadyToShipController@index')->name('system.order.ready-to-ship');
Route::get('order-totals','OrderController@order_totals')->name('system.order.totals');
Route::get('ready-to-ship-order-totals','ReadyToShipController@order_totals')->name('system.ready-to-ship-order.totals');
Route::get('order-picking-totals','OrderFulfillmentController@order_totals')->name('system.order.picking-totals');
Route::post('order-reassign_picker','OrderFulfillmentController@reassign_picker')->name('system.order.reassign_picker');
Route::get('order-print','OrderController@print')->name('system.order.print');
Route::get('order-packing/{id}','OrderFulfillmentController@packing')->name('system.order-fulfillment.packing');
Route::resource('/operator', 'OperatorController', ['as' => 'system']);
Route::resource('/type', 'TypeController', ['as' => 'system']);
Route::resource('/bill', 'BillController', ['as' => 'system']);

Route::resource('/station', 'StationController', ['as' => 'system']);
Route::get('/station-overview','StationController@stationOverview')->name('system.station.overview');

