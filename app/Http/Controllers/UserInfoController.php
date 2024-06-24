<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserInfoController extends Controller
{
    /**
     * Store user information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $userId)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|max:15',
            'id_type' => 'required|string|max:50',
            'id_number' => 'required|string|max:50',
            'ssn' => 'required|string|max:50',
            'country' => 'required|string|max:50',
            'state' => 'required|string|max:50',
            'city' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        // Find the user by ID
        $user = User::find($userId);

        // Check if user exists
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.'
            ], 404);
        }

        // Check if the user has already updated their information
        if ($user->phone_number || $user->id_type || $user->id_number || $user->ssn || $user->country || $user->state || $user->city) {
            return response()->json([
                'status' => false,
                'message' => 'User information can only be updated once.'
            ], 403);
        }

        // Update user information
        $user->update([
            'phone_number' => $request->phone_number,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'ssn' => $request->ssn,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
        ]);

        // Return the updated user data
        return response()->json([
            'status' => true,
            'message' => 'User information updated successfully.',
            'user' => $user,
        ], 200);
    }
}
