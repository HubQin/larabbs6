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

Route::prefix('v1')->namespace('Api')->middleware('change-locale')->name('api.v1.')->group(function() {
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
        // 小程序登录
        Route::post('weapp/authorizations', 'AuthorizationsController@weappStore')
            ->name('weapp.authorizations.store');
        // 小程序注册
        Route::post('weapp/users', 'UsersController@weappStore')
            ->name('weapp.users.store');
        // third party login
        Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->where('social_type', 'weixin')
            ->name('socials.authorizations.store');
        // update token
        Route::put('authorizations/current', 'AuthorizationsController@update')->name('authorizations.update');
        // delete token
        Route::delete('authorizations/current', 'AuthorizationsController@destroy')->name('authorizations.destroy');

        // User's topic
        Route::get('users/{user}/topics', 'TopicsController@userIndex')->name('users.topics.index');
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

        Route::get('links', 'LinksController@index')->name('links.index');
        Route::get('actived/users', 'UsersController@activedIndex')->name('actived.users.index');

        // should login
        Route::middleware('auth:api')->group(function () {
            // current login user's info
            Route::get('user', 'UsersController@me')->name('user.show');
            // Edit login User info
            Route::patch('user', 'UsersController@update')->name('user.update');
            // 小程序不支持patch，用put适配小程序
            Route::put('user', 'UsersController@update')
                ->name('user.update');
            // upload image
            Route::post('images', 'ImagesController@store')->name('images.store');

            Route::resource('topics', 'TopicsController')->only(['store', 'update', 'destroy']);

            // Reply topic
            Route::post('topics/{topic}/replies', 'RepliesController@store')->name('topics.replies.store');
            // Delete reply
            Route::delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')->name('topics.replies.destroy');
            // Notifications
            Route::get('notifications', 'NotificationsController@index')->name('notifications.index');
            Route::get('notifications/stats', 'NotificationsController@stats')
                ->name('notifications.stats');
            // Mark as read
            Route::patch('user/read/notifications', 'NotificationsController@read')
                ->name('user.notifications.read');
            // 兼容小程序
            Route::put('user/read/notifications', 'NotificationsController@read')
                ->name('user.notifications.read.put');

            // Permissions
            Route::get('user/permissions', 'PermissionsController@index')->name('user.permissions.index');
        });

    });

});
