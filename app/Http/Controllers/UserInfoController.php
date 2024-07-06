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

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.'
            ], 404);
        }

        if ($user->phone_number || $user->id_type || $user->id_number || $user->ssn || $user->country || $user->state || $user->city) {
            return response()->json([
                'status' => false,
                'message' => 'User information can only be updated once.'
            ], 403);
        }

        $user->update([
            'phone_number' => $request->phone_number,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'ssn' => $request->ssn,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User information updated successfully.',
            'user' => $user,
        ], 200);
    }

    public function uploadDocuments(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'id_front_photo' => 'required|file|max:5000|image',
            'id_back_photo' => 'required|file|max:5000|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 401);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($user->id_front_photo || $user->id_back_photo) {
            return response()->json([
                'status' => false,
                'message' => 'Documents already uploaded'
            ], 400);
        }

        $user->id_front_photo = $request->file('id_front_photo')->store('public');
        $user->id_back_photo = $request->file('id_back_photo')->store('public');
        $uploadDoc = true;
       $userVerified = true; 
        
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Documents uploaded successfully! Please complete your profile.',
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'pinCreated' => $pinCreated,
                'updateInfo' => $updateInfo,
                'uploadDoc' => $uploadDoc,
                'userVerified' => $userVerified,
            ]
        ]);
    }
}
