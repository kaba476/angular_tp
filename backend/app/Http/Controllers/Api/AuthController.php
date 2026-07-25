<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create($validated);
            Auth::login($user);
            $request->session()->regenerate();

            return response()->json(['user' => $user], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => "Erreur lors de l\'enregistrement: {$e->getMessage()}"
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Une erreur s'est produite lors de l'enregistrement: {$e->getMessage()}"
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($validated)) {
                return response()->json([
                    'message' => 'Identifiants invalides.'
                ], 401);
            }

            $request->session()->regenerate();

            return response()->json(['user' => $request->user()]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => "Erreur lors de la connexion: {$e->getMessage()}"
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Une erreur s'est produite lors de la connexion: {$e->getMessage()}"
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'message' => 'Déconnexion réussie.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Une erreur s'est produite lors de la déconnexion: {$e->getMessage()}"
            ], 500);
        }
    }

    public function me(Request $request)
    {
        try {
            return response()->json(['user' => $request->user()]);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Une erreur s'est produite lors de la récupération des informations de l'utilisateur: {$e->getMessage()}"
            ], 500);
        }
    }
}
