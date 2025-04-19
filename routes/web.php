<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ExpenseShareController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Group routes
    Route::resource('groups', GroupController::class);
    
    // Expense routes (nested under groups)
    Route::resource('groups.expenses', ExpenseController::class);
    
    // Redirect /expenses/{id} to the proper nested route
    Route::get('/expenses/{expense}', function ($expense) {
        $expenseModel = \App\Models\Expense::findOrFail($expense);
        return redirect()->route('groups.expenses.show', [$expenseModel->group, $expenseModel]);
    })->name('expenses.show');
    
    // Expense share routes
    Route::patch('/groups/{group}/expenses/{expense}/shares/{share}/mark-as-paid', 
        [ExpenseShareController::class, 'markAsPaid'])
        ->name('groups.expenses.shares.mark-as-paid');
    
    Route::patch('/groups/{group}/expenses/{expense}/shares/{share}/mark-as-unpaid', 
        [ExpenseShareController::class, 'markAsUnpaid'])
        ->name('groups.expenses.shares.mark-as-unpaid');

    // Group member management routes
    Route::post('/groups/{group}/members', [GroupController::class, 'addMember'])->name('groups.members.add');
    Route::delete('/groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.members.remove');
});
