<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;

Route::controller(EventController::class)->group(function(){
    Route::get('fullcalendar', [EventController::class, 'index'])->name('fullcalendar');
    Route::post('fullcalendarAjax', 'ajax');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
Route::post('/events/undo-delete', [EventController::class, 'undoDelete'])->name('events.undoDelete');
    Route::post('/add-news', [EventController::class, 'addNews'])->name('news.add');
    Route::post('/news/store', [EventController::class, 'addNews'])->name('news.store');
    Route::get('/news', [EventController::class, 'getNews'])->name('news.index');
    Route::put('/news/{id}', [EventController::class, 'updateNews'])->name('news.update');
    Route::delete('/news/{id}', [EventController::class, 'deleteNews'])->name('news.delete');
    Route::post('/news/restore', [EventController::class, 'restore'])->name('news.restore');
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/project/create', [ProjectController::class, 'create'])->name('projects.create'); // Fixed route name
    Route::post('/project', [ProjectController::class, 'store'])->name('projects.store'); // Fixed route name
    Route::get('/project/{project}', [ProjectController::class, 'show'])->name('projects.show'); // Fixed route name
    Route::get('/project/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit'); // Fixed route name
    Route::put('/project/{project}', [ProjectController::class, 'update'])->name('projects.update'); // Fixed route name
    Route::delete('/project/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy'); // Fixed route name


});


Route::get('/', [ProjectController::class, 'index'])->name('index');
require __DIR__.'/auth.php';

require __DIR__.'/admin-auth.php';

require __DIR__.'/farmer-auth.php';


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
