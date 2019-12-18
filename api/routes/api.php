<?php

Route::middleware('api')->group(function () {
    Route::post('login', 'AuthController@login');

    Route::middleware('tokenValidate')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('logout', 'AuthController@logout');
        });

        Route::prefix('activity')->group(function () {
            Route::post('activities', 'ActivityController@activities');
            Route::post('responsible', 'ActivityController@responsible');
            Route::post('status', 'ActivityController@status');
            Route::post('create', 'ActivityController@create');
            Route::post('{id}/update', 'ActivityController@update');
            Route::post('{id}/delete', 'ActivityController@delete');
        });
    });
});
