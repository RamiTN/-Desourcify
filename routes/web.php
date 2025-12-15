<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public pages
Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/templates', function () {
    return view('templates');
})->name('templates');

// Protected routes (authenticated & verified)
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard route (redirect admin users)
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard', ['user' => $user]);
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Credits page
    Route::get('/credit', function () {
        return view('credit');
    })->name('credits');

    // Contact page
    Route::get('/contact', function () {
        return view('contact');
    })->name('contact');

    // Protected image download route
    Route::post('/api/download-image', function (Request $request) {
        $user = $request->user();

        $request->validate([
            'image_url' => 'required|url',
            'photographer'=> 'required|string|max:255',
            'photo_id' => 'required|string|max:50'
        ]);

        if ($user->credits <= 0) {
            return response()->json(['error' => 'Insufficient credits. Please purchase more credits.'], 403);
        }

        try {
            // Fetch image from remote
            $response = Http::get($request->image_url);
            if (!$response->ok()) {
                return response()->json(['error' => 'Failed to fetch image'], 400);
            }

            // Save temporarily in public storage
            $filename = 'downloads/' . $request->photo_id . '.jpg';
            Storage::disk('public')->put($filename, $response->body());

            // Deduct credit
            $user->decrement('credits');

            // Return download URL
            $downloadUrl = Storage::url($filename);

            return response()->json([
                'success' => true,
                'remaining_credits' => $user->credits,
                'download_url' => $downloadUrl
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error downloading image: ' . $e->getMessage()], 500);
        }

    })->middleware('auth');

});

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admindashboard');
    })->name('admin.dashboard');
});

// API routes (public proxies for Pexels & Pixabay)
// API routes (public proxies for Pexels & Pixabay)
Route::get('/pexels/{query?}', function ($query = 'nature') {
    $apiKey = env('PEXELS_API_KEY');
    if (!$apiKey) return response()->json(['error' => 'API key not configured'], 500);

    $query = preg_replace('/[^a-zA-Z0-9\s-]/', '', $query);

    $response = Http::withHeaders(['Authorization' => $apiKey])
        ->get("https://api.pexels.com/v1/search", [  // ← Fixed: added /search
            'query' => $query,
            'per_page' => 6
        ]);

    if ($response->successful()) return $response->json();

    return response()->json([
        'error' => 'Failed to fetch images from Pexels', 
        'status' => $response->status()
    ], 500);
})->middleware('throttle:60,1');

Route::get('/pixabay/{query?}', function ($query = 'nature') {
    $apiKey = env('PIXABAY_API_KEY');
    if (!$apiKey) return response()->json(['error' => 'Pixabay API key not configured'], 500);

    $query = preg_replace('/[^a-zA-Z0-9\s-]/', '', $query);

    $response = Http::get("https://pixabay.com/api/", [  // ← Fixed: removed /docs/
        'key' => $apiKey,
        'q' => $query,
        'image_type' => 'photo',
        'per_page' => 6,
        'safesearch' => true
    ]);

    if ($response->successful()) return $response->json();

    return response()->json([
        'error' => 'Failed to fetch images from Pixabay', 
        'status' => $response->status()
    ], 500);
})->middleware('throttle:60,1');

require __DIR__.'/auth.php';




