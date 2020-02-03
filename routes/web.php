<?php

use Illuminate\Support\Facades\Route;
Route::group(['middleware' => ['web']], function () {
    Route::prefix('tenant')->group(function () {
        Route::get('', '\Tools4Schools\MultiTenant\Http\Controllers\TenantController@index')->name('tenant.selection');
        Route::post('select', '\Tools4Schools\MultiTenant\Http\Controllers\TenantController@select')->name('tenant.select');
    });
});