<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PageTemplateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebToastSettingsController;
use App\Http\Controllers\RoleController;
use Inertia\Inertia;

// Dashboard
Route::middleware(['auth', 'can:view dashboard'])->get('/dashboard', fn() => Inertia::render('Dashboard/Home'))->name('dashboard');

// Gestión de Usuarios
Route::middleware(['auth', 'can:view users'])->get('/dashboard/users', [UserController::class, 'index'])->name('dashboard.users.index');
Route::middleware(['auth', 'can:create users'])->get('/dashboard/users/create', [UserController::class, 'create'])->name('dashboard.users.create');
Route::middleware(['auth', 'can:create users'])->post('/dashboard/users', [UserController::class, 'store'])->name('dashboard.users.store');
Route::middleware(['auth', 'can:edit users'])->get('/dashboard/users/{user}/edit', [UserController::class, 'edit'])->name('dashboard.users.edit');
Route::middleware(['auth', 'can:edit users'])->put('/dashboard/users/{user}', [UserController::class, 'update'])->name('dashboard.users.update');
Route::middleware(['auth', 'can:delete users'])->delete('/dashboard/users/{user}', [UserController::class, 'destroy'])->name('dashboard.users.destroy');
Route::middleware(['auth', 'can:assign roles to users'])->get('/dashboard/users/{user}', [UserController::class, 'show'])->name('dashboard.users.show');

// Gestión de Roles
Route::middleware(['auth', 'can:view roles'])->get('/dashboard/roles', [RoleController::class, 'index'])->name('dashboard.roles');
Route::middleware(['auth', 'can:create roles'])->get('/dashboard/roles/create', [RoleController::class, 'create'])->name('dashboard.roles.create');
Route::middleware(['auth', 'can:create roles'])->post('/dashboard/roles/store', [RoleController::class, 'store'])->name('dashboard.roles.store');
Route::middleware(['auth', 'can:edit roles'])->get('/dashboard/roles/{role}/edit', [RoleController::class, 'edit'])->name('dashboard.roles.edit');
Route::middleware(['auth', 'can:edit roles'])->post('/dashboard/roles/{role}/update', [RoleController::class, 'update'])->name('dashboard.roles.update');

// Edición de la Web
Route::middleware(['auth', 'can:edit settings'])->get('/dashboard/web/toast/show', [WebToastSettingsController::class, 'show'])->name('dashboard.web.toast.show');
Route::middleware(['auth', 'can:edit settings'])->post('/dashboard/web/toast/update', [WebToastSettingsController::class, 'update'])->name('dashboard.web.toast.update');
Route::middleware(['auth', 'can:edit settings'])->post('/dashboard/web/toast/restore', [WebToastSettingsController::class, 'restore'])->name('dashboard.web.toast.restore');

// Gestión de Páginas
Route::middleware(['auth', 'can:view pages'])->get('/dashboard/pages', [PageController::class, 'index'])->name('dashboard.pages.index');
Route::middleware(['auth', 'can:view pages'])->get('/dashboard/pages/{page}', [PageController::class, 'show'])->name('dashboard.pages.show');
Route::middleware(['auth', 'can:create pages'])->get('/dashboard/pages/create', [PageController::class, 'create'])->name('dashboard.pages.create');
Route::middleware(['auth', 'can:create pages'])->post('/dashboard/pages', [PageController::class, 'store'])->name('dashboard.pages.store');
Route::middleware(['auth', 'can:edit pages'])->get('/dashboard/pages/{page}/edit', [PageController::class, 'edit'])->name('dashboard.pages.edit');
Route::middleware(['auth', 'can:edit pages'])->put('/dashboard/pages/{page}', [PageController::class, 'update'])->name('dashboard.pages.update');

// Gestión de Templates
Route::middleware(['auth', 'can:view templates'])->get('/dashboard/templates', [PageTemplateController::class, 'index'])->name('dashboard.templates.index');
Route::middleware(['auth', 'can:create templates'])->get('/dashboard/templates/create', [PageTemplateController::class, 'create'])->name('dashboard.templates.create');
Route::middleware(['auth', 'can:create templates'])->post('/dashboard/templates', [PageTemplateController::class, 'store'])->name('dashboard.templates.store');
Route::middleware(['auth', 'can:create templates'])->post('/dashboard/templates/generate', [PageTemplateController::class, 'generateWithAI'])->name('dashboard.templates.generate');
Route::middleware(['auth', 'can:view templates'])->get('/dashboard/templates/{template}', [PageTemplateController::class, 'show'])->name('dashboard.templates.show');
Route::middleware(['auth', 'can:view templates'])->post('/dashboard/templates/preview-template', [PageTemplateController::class, 'previewDraft'])->name('dashboard.templates.previewDraft');
Route::middleware(['auth', 'can:edit templates'])->get('/dashboard/templates/{template}/edit', [PageTemplateController::class, 'edit'])->name('dashboard.templates.edit');
Route::middleware(['auth', 'can:delete templates'])->delete('/dashboard/templates/{template}', [PageTemplateController::class, 'destroy'])->name('dashboard.templates.destroy');