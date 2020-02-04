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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::prefix('v1')->namespace('Api')->name('api.v1.')->group(function() {
    Route::middleware('throttle:' . config('api.rate_limits.sign'))->group(function () {
        // captcha
        Route::post('captchas', 'captchasController@store')->name('captchas.store');
        // send sms
        Route::post('verificationCodes', 'VerificationCodesController@store')->name('verificationCodes.store');
        // register
        Route::post('users', 'UsersController@store')->name('users.store');
        // login
        Route::post('authorizations', 'AuthorizationsController@store')
            ->name('authorizations.store');
        // third party login
        Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->where('social_type', 'weixin')
            ->name('socials.authorizations.store');
        // update token
        Route::put('authorizations/current', 'AuthorizationsController@update')->name('authorizations.update');
        // delete token
        Route::delete('authorizations/current', 'AuthorizationsController@destroy')->name('authorizations.destroy');
    });

    Route::middleware('throttle:'. config('api.rate_limits.access'))->group(function () {
        // for visitor
        // user info
        Route::get('users/{user}', 'UsersController@show')->name('users.show');

        // should login
        Route::middleware('auth:api')->group(function () {
            // current login user's info
            Route::get('user', 'UsersController@me')->name('user.show');
        });

    });

});
