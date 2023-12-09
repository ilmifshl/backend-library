<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    use HttpResponses;

    public function login(LoginUserRequest $request) {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->error(null, 'Email or password is incorrect!', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24);

        return $this->success([
            'user' => $user,
        ], 'User logged in successfully')->withCookie($cookie);
    }

    public function register(StoreUserRequest $request) {
        $data = $request->validated();

        $user = User::create([
            'role' => $request->role,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24);

        return $this->success([
            'user' => $user,
        ], 'User registered successfully')->withCookie($cookie);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        $cookie = cookie()->forget('token');

        return $this->success(null, 'Logged out successfully!')->withCookie($cookie);
    }

    public function user(Request $request) {
        return $this->success($request->user(), 'Data retrieved successfully!', 200);
    }

    public function getAllUsers() {
        $users = User::where('role', 1)->get();
    
        return $this->success($users, 'Users with role 1 retrieved successfully', 200);
    }
    
}