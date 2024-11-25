<?php

use App\Models\User;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\HomeController;
use App\Mail\WelcomeEmail;

// Google Authentication Routes
Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('/auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Download and save Google profile picture locally
        $avatarUrl = $googleUser->getAvatar();
        $avatarPath = 'profile_pictures/' . uniqid() . '.jpg';
        Storage::disk('public')->put($avatarPath, file_get_contents($avatarUrl));

        // Find or create user logic with a default password value
        $user = User::updateOrCreate([
            'email' => $googleUser->getEmail(),
        ], [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'profile_picture' => $avatarPath, // Store the local profile picture path
            'password' => bcrypt(Str::random(16)), // Assign a random password
        ]);

        // Check if the user is newly created 
        if ($user->wasRecentlyCreated) {
            // Send the welcome email 
            Mail::to($user->email)->send(new WelcomeEmail($user));
        }

        // Log in the user
        Auth::login($user);

        // Redirect to the dashboard
        return redirect('/home');
    } catch (\Exception $e) {
        // Log error for debugging
        logger()->error('Google Authentication Error: ' . $e->getMessage());
        return redirect('/')->with('error', 'Unable to authenticate with Google.');
    }
})->name('google.callback');

// Basic Route to Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// Protected Route to Dashboard (requires authentication and email verification)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Home Route
Route::get('/home', [HomeController::class, 'index'])->name('dashboard');

// Profile Management Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // Edit Profile
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Update Profile
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Delete Profile
    Route::put('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.update.picture'); // Update Profile Picture
    Route::delete('/profile/picture/{id}', [ProfileController::class, 'deletePicture'])->name('profile.delete.picture');
    Route::post('/profile/update-documents', [ProfileController::class, 'updateDocuments'])->name('profile.updateDocuments'); // Update Profile Documents
});

// Task Management Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index'); // List Tasks
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store'); // Create Task
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit'); // Edit Task
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update'); // Update Task
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy'); // Delete Task
    Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder'); // Reorder Tasks
    Route::post('/tasks/{id}/toggle', [TaskController::class, 'toggleTask'])->name('tasks.toggle'); // Toggle Task Completion
});

// Include additional authentication routes
require __DIR__ . '/auth.php';
