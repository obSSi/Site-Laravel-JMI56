<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

if (!defined('GDPR_RETENTION_DAYS')) {
    define('GDPR_RETENTION_DAYS', 365);
}

if (!function_exists('purgeOldContactRequests')) {
    function purgeOldContactRequests(): void
    {
        DB::table('contact_requests')
            ->where('created_at', '<', now()->subDays(GDPR_RETENTION_DAYS))
            ->delete();
    }
}

// Page publique
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/mentions-legales', function () {
    return view('legal.mentions-legales', [
        'ownerName' => (string) env('LEGAL_OWNER_NAME', 'JMI 56'),
        'ownerAddress' => (string) env('LEGAL_OWNER_ADDRESS', '78 Rue du Val, 56800 Ploermel'),
        'ownerPhonePrimary' => (string) env('LEGAL_OWNER_PHONE_PRIMARY', '06 14 41 80 99'),
        'ownerPhoneSecondary' => (string) env('LEGAL_OWNER_PHONE_SECONDARY', '02 97 93 07 83'),
        'contactEmail' => (string) env('LEGAL_CONTACT_EMAIL', 'contact@jmi56.fr'),
        'hostingProvider' => (string) env('LEGAL_HOSTING_PROVIDER', 'A completer'),
        'hostingAddress' => (string) env('LEGAL_HOSTING_ADDRESS', 'A completer'),
        'hostingPhone' => (string) env('LEGAL_HOSTING_PHONE', 'A completer'),
    ]);
})->name('legal.mentions');

Route::get('/politique-confidentialite', function () {
    return view('legal.politique-confidentialite', [
        'contactEmail' => (string) env('LEGAL_CONTACT_EMAIL', 'contact@jmi56.fr'),
        'retentionDays' => GDPR_RETENTION_DAYS,
    ]);
})->name('legal.privacy');

// Connexion admin
Route::get('/login', function (Request $request) {
    if ($request->session()->get('is_admin')) {
        return redirect()->route('home');
    }

    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    // Lock by client IP so lockout remains active regardless of page navigation.
    $throttleKey = 'admin-login|'.$request->ip();
    $maxAttempts = max((int) config('security.admin_login.max_attempts', 5), 1);

    if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
        $seconds = RateLimiter::availableIn($throttleKey);

        return back()
            ->withErrors(['login' => "Trop de tentatives. Reessayez dans {$seconds} secondes."])
            ->withInput();
    }

    if ($credentials['username'] === 'admin' && $credentials['password'] === 'admin123') {
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        $request->session()->put('is_admin', true);

        return redirect()->route('home');
    }

    $lockoutSeconds = max((int) config('security.admin_login.lockout_seconds', 300), 1);
    RateLimiter::hit($throttleKey, $lockoutSeconds);

    return back()
        ->withErrors(['login' => 'Identifiant ou mot de passe incorrect.'])
        ->withInput();
})->name('login.submit');

// Admin : listes par statut
Route::get('/admin', function (Request $request) {
    if (!$request->session()->get('is_admin')) {
        return redirect()->route('login');
    }

    purgeOldContactRequests();

    $requests = DB::table('contact_requests')
        ->where('status', 'pending')
        ->orderByDesc('created_at')
        ->get();

    return view('admin.index', [
        'requests' => $requests,
        'activeStatus' => 'pending',
        'searchQuery' => '',
        'searchMode' => false,
    ]);
})->name('admin');

Route::get('/admin/en-cours', function (Request $request) {
    if (!$request->session()->get('is_admin')) {
        return redirect()->route('login');
    }

    purgeOldContactRequests();

    $requests = DB::table('contact_requests')
        ->where('status', 'in_progress')
        ->orderByDesc('created_at')
        ->get();

    return view('admin.index', [
        'requests' => $requests,
        'activeStatus' => 'in_progress',
        'searchQuery' => '',
        'searchMode' => false,
    ]);
})->name('admin.in_progress');

Route::get('/admin/termine', function (Request $request) {
    if (!$request->session()->get('is_admin')) {
        return redirect()->route('login');
    }

    purgeOldContactRequests();

    $requests = DB::table('contact_requests')
        ->where('status', 'done')
        ->orderByDesc('created_at')
        ->get();

    return view('admin.index', [
        'requests' => $requests,
        'activeStatus' => 'done',
        'searchQuery' => '',
        'searchMode' => false,
    ]);
})->name('admin.done');

// Admin : recherche globale
Route::get('/admin/recherche', function (Request $request) {
    if (!$request->session()->get('is_admin')) {
        return redirect()->route('login');
    }

    purgeOldContactRequests();

    $query = trim((string) $request->query('q', ''));
    if ($query === '') {
        return redirect()->route('admin');
    }

    $requests = DB::table('contact_requests')
        ->where('name', 'like', '%' . $query . '%')
        ->orWhere('phone', 'like', '%' . $query . '%')
        ->orderByDesc('created_at')
        ->get();

    return view('admin.index', [
        'requests' => $requests,
        'activeStatus' => 'search',
        'searchQuery' => $query,
        'searchMode' => true,
    ]);
})->name('admin.search');

// Formulaire de contact
Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:120'],
        'phone' => ['required', 'string', 'regex:/^[0-9]{2}( [0-9]{2}){4}$/'],
        'message' => ['required', 'string', 'max:2000'],
    ]);

    $sanitized = [
        'name' => trim(strip_tags($validated['name'])),
        'phone' => trim(strip_tags($validated['phone'])),
        'message' => trim(strip_tags($validated['message'])),
    ];

    DB::table('contact_requests')->insert([
        'name' => $sanitized['name'],
        'phone' => $sanitized['phone'],
        'message' => $sanitized['message'],
        'status' => 'pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->to(route('home') . '#contact')
        ->with('contact_success', 'Votre demande a bien été envoyée.');
})->name('contact.submit');

// Admin : mise a jour du statut
Route::post('/admin/requests/{id}/status', function (Request $request, int $id) {
    if (!$request->session()->get('is_admin')) {
        return redirect()->route('login');
    }

    $status = $request->validate([
        'status' => ['required', 'in:pending,in_progress,done'],
    ])['status'];

    DB::table('contact_requests')
        ->where('id', $id)
        ->update(['status' => $status, 'updated_at' => now()]);

    $targetRoute = match ($status) {
        'in_progress' => route('admin.in_progress'),
        'done' => route('admin.done'),
        default => route('admin'),
    };

    return redirect()->to($targetRoute . '#request-' . $id);
})->name('admin.requests.status');

// Admin : suppression
Route::delete('/admin/requests/{id}', function (Request $request, int $id) {
    if (!$request->session()->get('is_admin')) {
        return redirect()->route('login');
    }

    DB::table('contact_requests')->where('id', $id)->delete();

    return back()
        ->with('admin_status', 'Demande supprimée.');
})->name('admin.requests.delete');

// Deconnexion
Route::post('/logout', function (Request $request) {
    $request->session()->forget('is_admin');
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->name('logout');
