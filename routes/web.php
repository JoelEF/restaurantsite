<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{menuItem:slug}', [MenuController::class, 'show'])->name('menu.show');

Route::get('/bestellen', [OrderController::class, 'index'])->name('order.index');
Route::post('/bestellen', [OrderController::class, 'store'])->name('order.store');
Route::get('/bestelling/{order}', [OrderController::class, 'confirmation'])->name('order.confirmation');
Route::get('/bestelling-volgen', [OrderController::class, 'track'])->name('order.track');

Route::get('/reservering', [ReservationController::class, 'index'])->name('reservation.index');
Route::post('/reservering', [ReservationController::class, 'store'])->name('reservation.store');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/keuken', [OrderController::class, 'kitchen'])->name('kitchen');
Route::get('/keuken/bestellingen', [OrderController::class, 'kitchenOrders'])->name('kitchen.orders');
Route::post('/keuken/status/{order}', [OrderController::class, 'updateStatus'])->name('kitchen.updateStatus');
Route::post('/keuken/geprint/{order}', [OrderController::class, 'markPrinted'])->name('kitchen.markPrinted');
Route::get('/keuken/bon/{order}', [OrderController::class, 'printBon'])->name('kitchen.bon');
