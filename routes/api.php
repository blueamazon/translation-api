<?php

use App\Http\Controllers\TranslationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if (app()->isLocal()) {
    Route::get('/token/create', function (Request $request) {
        $user = User::create([
            'name' => 'Demo User ' . Str::random(5),
            'email' => Str::random(10) . '@example.net',
            'password' => Hash::make('password'),
        ]);

        $token = $user->createToken('dev-token')->plainTextToken;

        return response()->json([
            'message' => 'Authenticated development token',
            'user' => $user,
            'token' => $token,
        ]);
    })->name('token.create');
}
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('translations', TranslationController::class);
    Route::get('/translations/export/{locale}', [TranslationController::class, 'export']);
    Route::get('/translations/search', [TranslationController::class, 'search']);
});
