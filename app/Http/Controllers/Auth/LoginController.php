<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        abort_if(! $user = $this->getAuthenticatedUser($request), HTTP_UNAUTHORIZED, 'Invalid User Credentials!');

        [$accessToken, $expiresAt] = $this->generateAccessCredentialsFor($user);

        return $this->jsonResponse(HTTP_SUCCESS, 'Logged in successfully', [
            'token' => $accessToken,
            'expires_at' => $expiresAt,
            'user' => $user->toArray()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->jsonResponse(HTTP_SUCCESS, 'User logged out successfully.');
    }

    private function getAuthenticatedUser(LoginRequest $request): ?User
    {
        if (! $user = User::where('email', $request->email)->first()) {
            return null;
        }

        return Hash::check($request->password, $user->password) ? $user : null;
    }

    private function generateAccessCredentialsFor(User $user, ?array $abilities = null): array
    {
        $token = $user->createToken($user->email);
        $expiresAt = Carbon::now()->addMinutes(config('sanctum.expiration'))->getTimestamp();
        $user->withAccessToken($token->accessToken);

        return [$token->plainTextToken, $expiresAt];
    }
}
