<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'home')->name('home');

Volt::route('/admin/login', 'admin.login')->name('admin.login');

Route::middleware('auth')->group(function () {
    Volt::route('/admin', 'admin.dashboard')->name('admin.dashboard');
});