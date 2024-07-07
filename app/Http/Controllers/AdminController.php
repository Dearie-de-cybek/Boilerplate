<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAllUsers(Request $request)
    {
        $users = User::all();
        return response()->json($users);
    }

    public function adminLogin(Request $request)
{
    $credentials = $request->only(['email', 'password']);
    if (auth()->attempt($credentials)) {
        // login successful, return a token or a success response
        $user = auth()->user();
        $token = $user->createToken('admin_token')->plainTextToken;
        return response()->json(['token' => $token]);
    } else {
        // login failed, return an error response
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

}

public function updateUser(Request $request, $userId)
{
    $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Define validation rules
        $rules = [
            'btc_balance' => 'nullable|numeric',
            'eth_balance' => 'nullable|numeric',
            'btc_unit' => 'nullable|numeric',
            'eth_unit' => 'nullable|numeric',
        ];

        // Validate request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update user with only validated and non-null data
        $validatedData = $validator->validated();
        $user->update($validatedData);

        return response()->json(['message' => 'User updated successfully']);
    }
}
