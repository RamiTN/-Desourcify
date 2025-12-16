<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================================================
// PUBLIC ROUTES
// ============================================================================

Route::view('/', 'welcome')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/terms', 'terms')->name('terms');
Route::view('/templates', 'templates')->name('templates');
Route::view('/video', 'video')->name('video');

// ============================================================================
// AUTHENTICATED ROUTES
// ============================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard - Auto-redirect admins to admin dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return $user->is_admin
            ? redirect()->route('admin.dashboard')
            : view('dashboard', compact('user'));
    })->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Credits & Contact Pages
    Route::view('/credit', 'credit')->name('credits');
    Route::view('/contact', 'contact')->name('contact');

    // Image Download (requires credits)
    Route::post('/api/download-image', function (Request $request) {
        $user = $request->user();
        
        // Validate incoming data
        $request->validate([
            'image_url'    => 'required|url',
            'photographer' => 'required|string|max:255',
            'photo_id'     => 'required|string|max:50',
        ]);

        // Check if user has enough credits
        if ($user->credits < 1) {
            return response()->json([
                'error' => 'Insufficient credits'
            ], 403);
        }

        // Fetch the image
        $response = Http::get($request->image_url);
        if (!$response->ok()) {
            return response()->json(['error' => 'Image fetch failed'], 400);
        }

        // Store the image
        $path = 'downloads/' . $request->photo_id . '.jpg';
        Storage::disk('public')->put($path, $response->body());

        // Deduct credit
        $user->decrement('credits');

        return response()->json([
            'success' => true,
            'remaining_credits' => $user->credits,
            'download_url' => Storage::url($path),
        ]);
    })->name('download.image');
});

// ============================================================================
// ADMIN ROUTES
// ============================================================================

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    
    // Admin Dashboard
    Route::get('/', function () {
        $user = Auth::user();
        
        if (!$user->is_admin) {
            abort(403, 'Unauthorized access to admin area.');
        }
        
        return view('admindashboard');
    })->name('admin.dashboard');
    
    // Manage Users
    Route::get('/users', function () {
        $user = Auth::user();
        
        if (!$user->is_admin) {
            abort(403, 'Unauthorized access.');
        }
        
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.users', compact('users'));
    })->name('admin.users');
    
    // View Statistics
    Route::get('/stats', function () {
        $user = Auth::user();
        
        if (!$user->is_admin) {
            abort(403, 'Unauthorized access.');
        }
        
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'total_credits' => User::sum('credits'),
            'recent_users' => User::orderBy('created_at', 'desc')->limit(5)->get(),
        ];
        
        return view('admin.stats', compact('stats'));
    })->name('admin.stats');
    
    // Manage Credits
    Route::get('/credits', function () {
        $user = Auth::user();
        
        if (!$user->is_admin) {
            abort(403, 'Unauthorized access.');
        }
        
        $users = User::orderBy('credits', 'desc')->paginate(20);
        
        return view('admin.credits', compact('users'));
    })->name('admin.credits');
    
    // Update User Credits
    Route::post('/credits/update', function (Request $request) {
        $user = Auth::user();
        
        if (!$user->is_admin) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'credits' => 'required|integer|min:0',
        ]);
        
        $targetUser = User::findOrFail($request->user_id);
        $targetUser->update(['credits' => $request->credits]);
        
        return redirect()->back()->with('success', 'Credits updated successfully!');
    })->name('admin.credits.update');
    
    // Pexels API Control
    Route::get('/pexels', function () {
        $user = Auth::user();
        
        if (!$user->is_admin) {
            abort(403, 'Unauthorized access.');
        }
        
        $apiConfig = [
            'pexels_key' => config('services.pexels.key') ? '***' . substr(config('services.pexels.key'), -4) : 'Not Set',
            'pixabay_key' => config('services.pixabay.key') ? '***' . substr(config('services.pixabay.key'), -4) : 'Not Set',
        ];
        
        return view('admin.pexels', compact('apiConfig'));
    })->name('admin.pexels');
    
    // Delete User
    Route::delete('/users/{id}', function ($id) {
        $user = Auth::user();
        
        if (!$user->is_admin) {
            abort(403, 'Unauthorized access.');
        }
        
        $targetUser = User::findOrFail($id);
        
        // Prevent deleting yourself
        if ($targetUser->id === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account from here!');
        }
        
        $targetUser->delete();
        
        return redirect()->back()->with('success', 'User deleted successfully!');
    })->name('admin.users.delete');
});

// ============================================================================
// PUBLIC API PROXIES (Rate Limited)
// ============================================================================

// Pexels API Proxy
Route::get('/pexels/{query?}', function ($query = 'nature') {
    $key = env('PEXELS_API_KEY');
    
    if (!$key) {
        return response()->json(['error' => 'PEXELS API key missing'], 500);
    }
    
    // Sanitize query
    $query = preg_replace('/[^a-zA-Z0-9\s-]/', '', $query);
    
    return Http::withHeaders([
        'Authorization' => $key
    ])->get('https://api.pexels.com/v1/search', [
        'query' => $query,
        'per_page' => 6,
    ])->json();
})->middleware('throttle:60,1');

//pexels video API Proxy
// ===============================
Route::get('/pexels/video/{query?}', function ($query = 'nature') {
    $key = env('PEXELS_API_KEY');

    if (!$key) {
        return response()->json(['error' => 'PEXELS API key missing'], 500);
    }

    // Sanitize query
    $query = preg_replace('/[^a-zA-Z0-9\s-]/', '', $query);

    $response = Http::withHeaders([
        'Authorization' => $key
    ])->get('https://api.pexels.com/videos/search', [
        'query' => $query,
        'per_page' => 6,
    ]);

    return $response->json();
})->middleware('throttle:60,1');

// ===============================
// VIDEO DOWNLOAD (requires credits)
// ===============================
Route::middleware(['auth'])->post('/api/download-video', function (Request $request) {
    $user = $request->user();
    $usedCredits = 3; // Cost per video

    if ($user->credits < $usedCredits) {
        return response()->json(['error' => 'Insufficient credits'], 403);
    }

    // Subtract credits
    $user->decrement('credits', $usedCredits);

    // You can optionally log download info here

    return response()->json([
        'success' => true,
        'remaining_credits' => $user->credits
    ]);
})->name('download.video');

// Pixabay API Proxy
Route::get('/pixabay/{query?}', function ($query = null) {
    $key = env('PIXABAY_API_KEY');
    
    if (!$key) {
        return response()->json(['error' => 'PIXABAY API key missing'], 500);
    }
    
    // Sanitize query
    $query = preg_replace('/[^a-zA-Z0-9\s-]/', '', $query);
    
    return Http::get('https://pixabay.com/api/', [
        'key' => $key,
        'q' => $query,
        'image_type' => 'photo',
        'per_page' => 6,
    ])->json();
})->middleware('throttle:60,1');

// ============================================================================
// AUTH ROUTES (Login, Register, Password Reset, etc.)
// ============================================================================

require __DIR__.'/auth.php';