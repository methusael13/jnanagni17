<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'PagesController@welcome');
Route::get('register/verify/{hash}/{token}', 'EmailController@regVerify');

Route::get('xcalliber-admin', 'EmailController@sendBulkWelcomeMail');

Route::get('/registrations', 'PagesController@registrations');
Route::post('/reg-details', 'PagesController@regDetails');
Route::post('evt-list', 'PagesController@getEvtList');
Route::post('reg-list', 'PagesController@getRegList');

/* Mobile Routes */
Route::get('mu', 'PagesController@mobile');
Route::get('mu/sponsors', 'PagesController@mobileSponsors');
Route::get('mu/about-us', 'PagesController@mobileAboutUs');
Route::get('mu/contact-us', 'PagesController@mobileContacts');

Route::post('evt-story', 'EventController@getStory');
Route::post('evt-img', 'EventController@getImages');
Route::post('evt-info', 'EventController@getInfo');
Route::post('evt-reg', 'EventController@webRouteRegister');
Route::post('evt-unreg', 'EventController@webRouteUnregister');

Route::post('api/event', 'EventController@apiRoute');
Route::post('api/user', 'RegistrationController@apiRoute');

Route::post('login', 'RegistrationController@webRouteLogin');
Route::post('logout', 'RegistrationController@webRouteLogout');
Route::post('register', 'RegistrationController@webRouteRegister');
Route::post('mail/resend', 'RegistrationController@resend');
