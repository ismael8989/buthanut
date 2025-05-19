<?php

namespace App\Http\Controllers;

use App\Models\Carnet;
use App\Models\Link;
use App\Models\Transaction;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CarnetController extends Controller
{
    public function generateLinkToken() {
        $valid_characters = array_merge(
            range('0', '9'),
            range('A', 'Z'),
            range('a', 'z')
        );

        $out_string = "";

        for($i = 0; $i < 64; $i++)  {
            $out_string .= (string) random_int(0, count($valid_characters)-1);
        }

        return $out_string;
    }

    public function index(Request $request) {
        $user = $request->user;

        if (!$user) {
            return response()->json([
                'error' => "Unauthorized",
            ], 403);
        }

        try {
            $carnets = Carnet::where('buthanut_id', $user->id)->get();
            return response()->json([
                'carnets' => $carnets
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => "Something went wrong",
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id) {
        $user = $request->user;

        if (!$user) {
            return response()->json([
                'error' => "Unauthorized",
            ], 403);
        }

        try {
            $carnet = Carnet::find($id);

            if (!$carnet) {
                return response()->json([
                    'error' => "Carnet not found",
                ], 404);
            }

            return response()->json([
                'carnet' => $carnet
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => "Something went wrong",
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'client' => "required|string|max:255"
        ]);

        $user = $request->user;

        if (!$user) {
            return response()->json([
                'error' => "Unauthorized",
            ], 403);
        }

        try {
            $result = DB::transaction(function () use ($user, $validated) {
                $carnet = Carnet::create([
                    'buthanut_id' => $user->id,
                    'client' => $validated['client'],
                ]);

                Link::create([
                    'carnet_id' => $carnet->id,
                    'token' => $this->generateLinkToken(),
                ]);

                return $carnet;
            });

            return response()->json([
                'carnet' => $result
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'error' => "Something went wrong",
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'client' => "required|string|max:255"
        ]);

        $user = $request->user;

        if (!$user) {
            return response()->json([
                'error' => "Unauthorized",
            ], 403);
        }

        try {
            $carnet = Carnet::find($id);

            if (!$carnet) {
                return response()->json([
                    'error' => "Carnet not found",
                ], 404);
            }

            $carnet->client = $validated['client'];
            $carnet->save();

            return response()->json([
                'carnet' => $carnet
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => "Something went wrong",
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id) {
        $user = $request->user;

        if (!$user) {
            return response()->json([
                'error' => "Unauthorized",
            ], 403);
        }

        try {
            $carnet = Carnet::find($id);

            if (!$carnet) {
                return response()->json([
                    'error' => "Carnet not found",
                ], 404);
            }

            $carnet->delete();

            return response()->json([
                'message' => "Carnet deleted successfully"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => "Something went wrong",
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getContent($token) {
        $link = Link::where('token', $token)->first();

        if (!$link) {
            return response()->json(['error' => 'Invalid or expired token'], 404);
        }

        $carnet = $link->carnet()->with(['transactions', 'user'])->first();

        if (!$carnet) {
            return response()->json(['error' => 'Carnet not found'], 404);
        }

        return response()->json([
            'client' => $carnet->client,
            'buthanut' => [
                'firstname' => $carnet->user->firstname ?? null,
                'lastname' => $carnet->user->lastname ?? null,
            ],
            'transactions' => $carnet->transactions
        ], 200);
    }
}
