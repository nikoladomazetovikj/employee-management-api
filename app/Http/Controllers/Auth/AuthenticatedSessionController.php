<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = $request->user();
        $company = $request->user()->company;

        foreach ($company as $c) {
            $roleId = $c->pivot->role_id;
            $role = Role::getDescription($roleId);
        }

        return response()->json(['token' => $token, 'user' => $request->user(), 'role' => $role], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
