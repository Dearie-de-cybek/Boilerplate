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

        if ($user->total_balance < $request->price) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

         $invoiceNo = rand(100000, 999999);

         $user->total_balance -= $request->price;
        $user->save();

        $createdAt = $user->created_at->toDateTimeString();

        

        $response = [
            'invoice_no' => $invoiceNo,
            'type' => $request->type,
            'assets' => $request->assets,
            'price' => $request->price,
            'status' => 'Pending',
            'createdAt' => $createdAt,
        ];

        return response()->json($response, 200);
    }
}
