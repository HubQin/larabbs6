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

        Route::get('categories', 'CategoriesController@index')->name('categories.index');

        Route::resource('topics', 'TopicsController')->only(['index', 'show']);
        // Reply list (On Topic)
        Route::get('topics/{topic}/replies', 'RepliesController@index')
            ->name('topics.replies.index');
        // Reply list(On user)
        Route::get('users/{user}/replies', 'RepliesController@userIndex')
            ->name('user.replies.index');

        // should login
        Route::middleware('auth:api')->group(function () {
            // current login user's info
            Route::get('user', 'UsersController@me')->name('user.show');
            // Edit login User info
            Route::patch('user', 'UsersController@update')->name('user.update');
            // upload image
            Route::post('images', 'ImagesController@store')->name('images.store');

            Route::resource('topics', 'TopicsController')->only(['store', 'update', 'destroy']);
            // User's topic
            Route::get('users/{user}/topics', 'TopicsController@userIndex')->name('users.topics.index');

            // Reply topic
            Route::post('topics/{topic}/replies', 'RepliesController@store')->name('topics.replies.store');
            // Delete reply
            Route::delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')->name('topics.replies.destroy');
        });

    });

});
