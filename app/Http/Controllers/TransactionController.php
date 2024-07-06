<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function store($userId, Request $request)
    {
        // Validate the request data
        $request->validate([
            'type' => 'required|string',
            'method' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        // Find the user by ID
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $invoiceNo = rand(100000, 999999);

        $createdAt = $user->created_at->toDateTimeString();

        $response = [
            'invoice_no' => $invoiceNo,
            'type' => $request->type,
            'method' => $request->method,
            'amount' => $request->amount,
            'createdAt' => $createdAt,
        ];

        return response()->json($response, 200);
    }

    public function withdraw($userId, Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Create a new transaction
        $invoiceNo = rand(100000, 999999);

        $createdAt = $user->created_at->toDateTimeString();


        $response = [
            'invoice_no' => $invoiceNo,
            'amount' => $request->amount,
            'status' => 'pending',
            'createdAt' => $createdAt,
        ];

        return response()->json($response, 200);
    }
}
