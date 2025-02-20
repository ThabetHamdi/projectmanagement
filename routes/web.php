<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTeamController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\DeveloperDashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'      => Route::has('login'),
        'canRegister'   => Route::has('register'),
        'laravelVersion'=> Application::VERSION,
        'phpVersion'    => PHP_VERSION,
    ]);
})->name('home');

// General Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications', function () {
        return Inertia::render('Notifications', [
            'notifications' => auth()->user()->unreadNotifications,
        ]);
    })->name('notifications');

    // Tasks (index & store via project)
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    // Other Task routes (update, etc.) via resource (except index & store)
    Route::resource('tasks', TaskController::class)->except(['index', 'store']);

    // Comments
    Route::get('/tasks/{task}/comments', [TaskCommentController::class, 'index'])->name('tasks.comments.index');
    Route::post('/tasks/{task}/comments', [TaskCommentController::class, 'store'])->name('tasks.comments.store');
    Route::delete('/comments/{comment}', [TaskCommentController::class, 'destroy'])->name('comments.destroy');

    // Documents
    Route::post('/tasks/{task}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Projects Routes
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Project Team Routes (available for all authenticated users, if needed)
    Route::get('/projects/{project}/team', [ProjectTeamController::class, 'index'])->name('projects.team.index');
    Route::post('/projects/{project}/team', [ProjectTeamController::class, 'store'])->name('projects.team.store');
    Route::delete('/projects/{project}/team/{user}', [ProjectTeamController::class, 'destroy'])->name('projects.team.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/users', UserController::class)->names('admin.users');
});

// Manager Routes
Route::middleware(['auth', 'role:manager'])->prefix('manager')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
    Route::resource('projects', ProjectController::class);
    // Team management for projects (manager-specific if desired)
    Route::get('/projects/{project}/team', [ProjectTeamController::class, 'index'])->name('projects.team');
    Route::post('/projects/{project}/team', [ProjectTeamController::class, 'store']);
    Route::delete('/projects/{project}/team/{user}', [ProjectTeamController::class, 'destroy']);
});

// Developer Routes
Route::middleware(['auth', 'role:developer'])->prefix('developer')->group(function () {
    Route::get('/dashboard', [DeveloperDashboardController::class, 'index'])->name('developer.dashboard');
    Route::get('/dashboard/overdue', [DeveloperDashboardController::class, 'show_overdue_tasks'])->name('developer.dashboard.overdue');
    // Note: Task update, comment, and document routes are already defined above.
});

// Authentication routes
require __DIR__.'/auth.php';




Route::middleware(['auth'])->group(function () {
    Route::get('/developer/dashboard', [DeveloperDashboardController::class, 'dashboard'])
         ->name('developer.dashboard');
});
use App\Http\Controllers\NotificationController;

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
