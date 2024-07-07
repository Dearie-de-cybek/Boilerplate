<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $user = auth()->user();
        $token = $user->createToken('admin_token')->plainTextToken;
        return response()->json(['token' => $token]);
    } else {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

}

public function updateUser(Request $request, $userId)
{
    // Find the user by ID
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Define validation rules
    $rules = [
        'btc_balance' => 'nullable|numeric',
        'eth_balance' => 'nullable|numeric',
        'usdt_balance' => 'nullable|numeric',
        'status' => 'nullable|string',
        'total_balance' => 'nullable|numeric',
        'amount' => 'nullable|numeric',
        'price' => 'nullable|numeric'
    ];

    // Validate request data
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Get the validated data
    $validatedData = $validator->validated();

    $user->update($validatedData);

    

    try {
        DB::transaction(function () use ($user, $validatedData) {
          $user->update($validatedData);
        });
        return response()->json(['message' => 'User updated successfully', 'data' => $validatedData]);
      } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to update user', 'message' => $e->getMessage()], 500);
      }
}
}
