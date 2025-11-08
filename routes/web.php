<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Volt::route('login', 'login')->name('login');
    Volt::route('forgot-password', 'forgot-password')->name('password.request');
});
