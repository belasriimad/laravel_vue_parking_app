<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //
    public function updateUserInfos(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        auth()->user()->update([
            'name' => $request->name
        ]);

        return response()->json([
            'user' => auth()->user()
        ]);
    }

    public function updateUserPassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'max:255', 'current_password'],
            'password' => ['required', 'max:255']
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Your password has been updated'
        ]);
    }
}
