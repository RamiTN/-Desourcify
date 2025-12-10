<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

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

Route::get('/about', function () {
    return view('about');
})->name('about');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Pexels API proxy route - PUBLIC (anyone can browse images)
Route::get('/api/pexels/{query?}', function ($query = 'nature') {
    $apiKey = env('PEXELS_API_KEY');

    if (!$apiKey) {
        return response()->json([
            'error' => 'API key not configured'
        ], 500);
    }

    // Sanitize query parameter
    $query = preg_replace('/[^a-zA-Z0-9\s-]/', '', $query);

    $response = Http::withHeaders([
        'Authorization' => $apiKey,
    ])->get("https://api.pexels.com/v1/search", [
        'query' => $query,
        'per_page' => 6
    ]);

    if ($response->successful()) {
        return $response->json();
    } else {
        return response()->json([
            'error' => 'Failed to fetch images from Pexels',
            'status' => $response->status()
        ], 500);
    }
})->middleware('throttle:60,1');

// Download image route - PROTECTED (must be logged in and have credits)
Route::post('/api/download-image', function (Request $request) {
    $user = $request->user();
    // Validate request
    $request->validate([
        'image_url' => 'required|url',
        'photographer' => 'required|string|max:255',
        'photo_id' => 'required|string|max:50'
    ]);
    
    // Check if user has credits
    if ($user->credits <= 0) {
        return response()->json([
            'error' => 'Insufficient credits. Please purchase more credits to continue downloading.'
        ], 403);
    }
    
    // Deduct credit
    $user->decrement('credits');
    
    // Optional: Log the download
    // \App\Models\Download::create([
    //     'user_id' => $user->id,
    //     'photo_id' => $request->photo_id,
    //     'photographer' => $request->photographer,
    //     'image_url' => $request->image_url,
    // ]);
    
    return response()->json([
        'success' => true,
        'remaining_credits' => $user->credits,
        'message' => 'Download successful! You have ' . $user->credits . ' credits remaining.'
    ]);
})->middleware('auth');

Route::get('/contact', function () {
    return view('contact');
})->middleware(['auth', 'verified'])->name('contact');

Route::get('/credit', function () {
    return view('credit');
})->middleware(['auth', 'verified'])->name('credits');

require __DIR__.'/auth.php';