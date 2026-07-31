<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMilestoneController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', DashboardController::class)
        ->middleware('permission:dashboard.view')
        ->name('dashboard');
    Route::redirect('/home', '/dashboard')->name('home');
    Route::get('/reports/income', [ReportController::class, 'income'])
        ->middleware('permission:reports.view')
        ->name('reports.income');
    Route::get('/reports/income/export', [ReportController::class, 'exportIncome'])
        ->middleware('permission:reports.export')
        ->name('reports.income.export');

    Route::resource('clients', ClientController::class)
        ->middlewareFor(['index', 'show'], 'permission:clients.view')
        ->middlewareFor(['create', 'store'], 'permission:clients.create')
        ->middlewareFor(['edit', 'update'], 'permission:clients.update')
        ->middlewareFor(['destroy'], 'permission:clients.delete');
    Route::resource('projects', ProjectController::class)
        ->middlewareFor(['index', 'show'], 'permission:projects.view')
        ->middlewareFor(['create', 'store'], 'permission:projects.create')
        ->middlewareFor(['edit', 'update'], 'permission:projects.update')
        ->middlewareFor(['destroy'], 'permission:projects.delete');
    Route::post('/projects/{project}/documents', [DocumentController::class, 'storeForProject'])
        ->middleware('permission:documents.manage')
        ->name('projects.documents.store');
    Route::resource('projects.milestones', ProjectMilestoneController::class)
        ->except(['index', 'show'])
        ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'permission:project-workflow.manage');
    Route::resource('projects.tasks', ProjectTaskController::class)
        ->except(['index', 'show'])
        ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'permission:project-workflow.manage');
    Route::post('/projects/{project}/tasks/{task}/documents', [DocumentController::class, 'storeForTask'])
        ->middleware('permission:documents.manage')
        ->name('projects.tasks.documents.store');
    Route::resource('invoices', InvoiceController::class)
        ->middlewareFor(['index', 'show'], 'permission:invoices.view')
        ->middlewareFor(['create', 'store'], 'permission:invoices.create')
        ->middlewareFor(['edit', 'update'], 'permission:invoices.update')
        ->middlewareFor(['destroy'], 'permission:invoices.delete');
    Route::resource('expenses', ExpenseController::class)
        ->middlewareFor(['index', 'show'], 'permission:expenses.view')
        ->middlewareFor(['create', 'store'], 'permission:expenses.create')
        ->middlewareFor(['edit', 'update'], 'permission:expenses.update')
        ->middlewareFor(['destroy'], 'permission:expenses.delete');
    Route::post('/clients/{client}/documents', [DocumentController::class, 'storeForClient'])
        ->middleware('permission:documents.manage')
        ->name('clients.documents.store');
    Route::post('/invoices/{invoice}/documents', [DocumentController::class, 'storeForInvoice'])
        ->middleware('permission:documents.manage')
        ->name('invoices.documents.store');
    Route::post('/expenses/{expense}/documents', [DocumentController::class, 'storeForExpense'])
        ->middleware('permission:documents.manage')
        ->name('expenses.documents.store');
    Route::resource('invoices.payments', PaymentController::class)
        ->except(['index', 'show'])
        ->middlewareFor(['create', 'store'], 'permission:payments.create')
        ->middlewareFor(['edit', 'update'], 'permission:payments.update')
        ->middlewareFor(['destroy'], 'permission:payments.delete');
    Route::post('/invoices/{invoice}/payments/{payment}/documents', [DocumentController::class, 'storeForPayment'])
        ->middleware('permission:documents.manage')
        ->name('invoices.payments.documents.store');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])
        ->middleware('permission:documents.view')
        ->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])
        ->middleware('permission:documents.manage')
        ->name('documents.destroy');
});
