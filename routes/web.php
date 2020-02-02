<?php

use Illuminate\Support\Facades\Route;


Route::get('/login/', '\Tools4Schools\MultiTenant\Http\Controllers\Auth\LoginController@redirectToProvider')->name('login');
Route::get('/auth/callback', '\Tools4Schools\MultiTenant\Http\Controllers\Auth\LoginController@handleProviderCallback');
Route::get('/logout','\Tools4Schools\MultiTenant\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::prefix('tenant')->group(function (){
    Route::get('','\Tools4Schools\MultiTenant\Http\Controllers\TenantController@index')->name('tenant.selection');
    Route::post('select','\Tools4Schools\MultiTenant\Http\Controllers\TenantController@select')->name('tenant.select');
});