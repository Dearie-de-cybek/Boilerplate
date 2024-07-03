<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validateUser = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = Auth::user();
            
            // Return the user ID and email in the response
            return response()->json([
                'status' => true,
                'message' => 'Login Success',
                'user' => $user,
            ]);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'Invalid username or password'
        ], 401);
    }
}
