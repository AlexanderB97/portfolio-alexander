<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'home')->name('home');

Volt::route('/admin/login', 'admin.login')->name('admin.login');

Route::middleware('auth')->group(function () {
    Volt::route('/admin', 'admin.dashboard')->name('admin.dashboard');
    Volt::route('/admin/profile', 'admin.profile')->name('admin.profile');
    Volt::route('/admin/projects/create', 'admin.projects.form')->name('admin.projects.create');
    Volt::route('/admin/projects/{project}/edit', 'admin.projects.form')->name('admin.projects.edit');
});