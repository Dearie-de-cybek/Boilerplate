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
    

    public function login(Request $request)
    {
        $validateUser = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = Auth::user();
            
            // Generate a unique token for the user
            $token = $this->generateToken();
    
            // Store the token in the database
            $user->update(['api_token' => $token]);
    
            // Return the token in the response
            return response()->json([
                'status' => true,
                'message' => 'Login Success',
                'token' => $token
            ]);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'Invalid username or password'
        ], 401);
    }

    protected function generateToken()
{
    return Str::random(60); 
}

   
}
