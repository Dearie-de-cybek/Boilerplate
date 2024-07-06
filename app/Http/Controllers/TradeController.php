<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class TradeController extends Controller
{
    public function placeTrade($userId, Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'assets' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

         $invoiceNo = rand(100000, 999999);

        $createdAt = $user->created_at->toDateTimeString();

        $status = $user->status;

        $response = [
            'invoice_no' => $invoiceNo,
            'type' => $request->type,
            'assets' => $request->assets,
            'price' => $request->price,
            'status' => $status,
            'createdAt' => $createdAt,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ];

        return response()->json($response, 200);
    }
}
