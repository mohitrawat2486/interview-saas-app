<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InterviewController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Candidate\SubmissionController;
use App\Http\Controllers\Reviewer\ReviewController;
use App\Http\Controllers\VideoStreamController;
use App\Http\Controllers\ProfileController;

Route::get('/', fn() => view('welcome'));

// Auth routes from Breeze
require __DIR__.'/auth.php';

// Admin / Reviewer dashboards
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':admin,reviewer'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
});

// Admin: manage interviews, questions, invitations
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        // Keep index as a simple GET
        Route::get('interviews', [InterviewController::class, 'index'])
            ->name('interviews.index');

        // Restore the rest (this creates admin.interviews.edit, create, store, update, destroy, show)
        Route::resource('interviews', InterviewController::class)->except(['index']);

        Route::resource('interviews.questions', QuestionController::class)->shallow();
        Route::resource('interviews.invitations', InvitationController::class)
            ->only(['index','create','store','destroy']);
    });


// Candidate flow via secure token 
Route::get('/i/{token}', [SubmissionController::class, 'start'])->name('candidate.start');
Route::post('/i/{token}/begin', [SubmissionController::class, 'begin'])->name('candidate.begin');
Route::get('/s/{submission}/q/{order}', [SubmissionController::class, 'question'])->name('candidate.question');
Route::post('/s/{submission}/q/{question}/upload', [SubmissionController::class, 'upload'])->name('candidate.upload');
Route::post('/s/{submission}/finalize', [SubmissionController::class, 'finalize'])->name('candidate.finalize');

// Reviewers
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class.':admin,reviewer'])
    ->prefix('admin')->name('review.')->group(function () {
        Route::get('/submissions', [ReviewController::class, 'index'])->name('index');
        Route::get('/submissions/{submission}', [ReviewController::class, 'show'])->name('show');
        Route::post('/submissions/{submission}', [ReviewController::class, 'store'])->name('store');
    });


// Secure streaming 
Route::middleware(['auth'])->get('/answer/{answer}/stream', [VideoStreamController::class, 'stream'])
    ->name('answer.stream');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});    
