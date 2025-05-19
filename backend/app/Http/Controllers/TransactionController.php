<?php

namespace App\Http\Controllers;

use App\Models\Carnet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionController extends Controller
{
    public function index(Request $request, $carnet_id) {
        $user = $request->user;

        if(!$user || Carnet::find($carnet_id)->user_id !== $user->id) {
            return response()->json([
                'error' => "Unauthorized",
            ], 403);
        }

        try {
            $transactions = Transaction::where('carnet_id', $carnet_id)->get();
            return response()->json([
                'transactions' => $transactions,
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'error' => "An error has occured",
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request) {
        $user = $request->user;

        $validated = $request->validate([
            'product_name' => "required|string|max:255",
            'quantity' => "required|float|min:1",
            'total_price' => "required|float|min:0",
            'carnet_id' => 'required|exists:carnets,id',
        ]);

        $carnet = Carnet::find($validated['carnet_id']);
        if ($carnet->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            Transaction::create([
                'product_name' => $validated["product_name"],
                'quantity' => $validated["quantity"],
                'total_price' => $validated["total_price"],
            ]);
            return response()->json([
                'message' => "Transaction created successfully",
            ], 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => "An error has occured",
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'product_name' => "required|string|max:255",
            'quantity' => "required|float|min:1",
            'total_price' => "required|float|min:0",
        ]);

        $user = $request->user;
        $transaction = Transaction::find($id);

        if(!$transaction) {
            return response()->json([
                'error' => "Transaction not found",
            ], 404);
        }

        if(!$user || Carnet::find($transaction->carnet_id)->user_id !== $user->id) {
            return response()->json([
                'error' => "Unauthorized",
            ], 403);
        }

        try {
            $transaction->update([
                'product_name' => $validated["product_name"],
                'quantity' => $validated["quantity"],
                'total_price' => $validated["total_price"],
            ]);
            return response()->json([
                'message' => "Transaction updated successfully",
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'error' => "An error has occured",
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id) {
        $user = $request->user;
        $transaction = Transaction::find($id);

        if(!$transaction) {
            return response()->json([
                'error' => "Transaction not found",
            ], 404);
        }

        if(!$user || Carnet::find($transaction->carnet_id)->user_id !== $user->id) {
            return response()->json([
                'error' => "Unauthorized",
            ], 403);
        }

        try {
            $transaction->delete();
            return response()->json([
                'message' => "Transaction updated successfully",
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'error' => "An error has occured",
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function aggregate(Request $request, $carnet_id) {
        $user = $request->user;
        $carnet = Carnet::find($carnet_id);

        if(!$carnet) {
            return response()->json([
                'error' => "Carnet not found",
            ], 404);
        }

        if(!$user || $carnet->user_id !== $user->id) {
            return response()->json([
                'error' => "Unauthorized",
            ], 403);
        }
        
        $transactions = Transaction::where('carnet_id', $carnet->id)->get();

        $validated = $request->validate([
            'paid_amount' => "required|float|min:0",
        ]);

        $carnet_total = 0;

        foreach($transactions as $transaction) {
            $carnet_total += $transaction->total_price;
        }

        if($validated['paid_amount'] > $carnet_total) {
            return response()->json([
                'error' => "A client can't pay more the total amount of the Carnet",
            ], 400);
        }

        try {
            $remaining = $carnet_total - $validated['paid_amount'];
            DB::transaction(function() use ($transactions, $remaining) {
                $transactions->delete();
                Transaction::create([
                    'product_name' => "الباقي",
                    'quantity' => 1,
                    'total_price' => $remaining,
                ]);
            });
        } catch(Exception $e) {
            return response()->json([
                'error' => "An error has occured",
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
