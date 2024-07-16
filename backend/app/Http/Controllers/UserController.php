<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function store(Request $request) 
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email','max:255', 'unique:users'],
            'password' => ['required', 'min:8','max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('new_user')->plainTextToken, 
        ]);
    }

    public function auth(Request $request) 
    {
        $request->validate([
            'email' => ['required', 'string', 'email','max:255'],
            'password' => ['required', 'min:8','max:255'],
        ]);

        $user = User::whereEmail($request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'These credentials do not match any of our records'
            ]);
        }

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('new_user')->plainTextToken, 
        ]);
    }

    public function logout(Request $request) 
    {
        $request->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
