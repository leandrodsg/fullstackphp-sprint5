<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('subscriptions.index');
})->middleware(['auth' /* , 'verified' */])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('services', ServiceController::class);
    
    Route::resource('subscriptions', SubscriptionController::class);
    
    // Rota de teste para verificar problema 419
    Route::get('/test-419', function () {
        return view('test-419');
    })->name('test.419');
    
    Route::post('/test-csrf', function () {
        return back()->with('success', 'CSRF funcionando! Erro 419 foi resolvido.');
    })->name('test.csrf');
});

require __DIR__.'/auth.php';
