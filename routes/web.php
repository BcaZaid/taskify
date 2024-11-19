<?php
use App\Models\User;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('/auth/google/callback', function () {
    $googleuser = Socialite::driver('google')->stateless()->user();

    //find or create user logic with a default password value
    $user = User::updateOrCreate([
        'email' => $googleuser->getEmail(),
    ], [
        'name' => $googleuser->getName(),
        'google_id' => $googleuser->getId(),
        'password' => bcrypt(Str::random(16)), // Assign a random password
    ]);

    Auth::login($user);

    return redirect('/dashboard');
})->name('google.callback');

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
});

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
// Route to show the form for editing a task
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');

// Route to update the task
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

// Route to delete the task
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');

require __DIR__ . '/auth.php';
