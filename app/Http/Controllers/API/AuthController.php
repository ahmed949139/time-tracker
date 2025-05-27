<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $data = [];
        $data["token"] = $user->createToken('time-tracker-token')->plainTextToken;
        $data["name"] = $user->name;
        $data["email"] = $user->email;
        $data["message"] = "User registered successfully";

        return response()->json($data, 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
			'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!auth()->attempt($request->only(['email', 'password']))) {
            throw ValidationException::withMessages([
                'error' => ['The credentials are incorrect!']
            ]);
        }

        $data = [];
        $data["token"] = $user->createToken('time-tracker-token')->plainTextToken;
        $data["name"] = $user->name;
        $data["email"] = $user->email;
        $data["message"] = "User logged in successfully";

        return response()->json($data, 200);
    }
    
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
