<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\VerificationController;

class PasswordResetController extends Controller
{
    public function requestPasswordReset(Request $request) {
        $validated = $request->validate([
            'tel' => 'required|string',
        ]);

        $user = User::where('tel', $validated['tel'])->first();

        if(!$user) {
            return response()->json([ 'error' => "There is no registered user with the tel number you provided" ], 404);
        }

        DB::table("password_reset_requests")->insert([
            'tel' => $validated['tel'],
            'exp' => now()->addMinutes((int) env('PASSWORD_RESET_EXPIRY_MINUTES')),
        ]);
        
        return VerificationController::sendVN($request->tel, random_int(100000, 999999));
    }

    public function checkVerificationNumberPasswordReset(Request $request) {
        $tel = $request->tel;
        $verification_number = $request->verification_number;

        $VN = DB::table('verification_numbers')
            ->where('tel', $tel)
            ->first();

        if (!$VN || $VN->exp < now() || !Hash::check($verification_number, $VN->verification_number)) {
            return response()->json([
                'error' => "Invalid or expired verification number",
            ], 400);
        }

        try {
            DB::beginTransaction();
            DB::table('password_reset_requests')->where('tel', $tel)->delete();
            DB::table('password_reset_requests')->insert([
                'tel' => $tel,
                'exp' => now()->addMinutes((int) env('PASSWORD_RESET_EXPIRY_MINUTES')),
            ]);
            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();            
            return response()->json([
                'error' => "An error has occurred while processing your request",
                'details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }

        return response()->json([
            'message' => "Verification was successful"
        ], 200);
    }

    public function resetPassword(Request $request) {
        $validated = $request->validate([
            'tel' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if (!preg_match('/\d/', $validated['password'])) {
            return response()->json(["error" => "Password must contain at least 1 number"], 422);
        }

        if (!preg_match('/[a-z]/', $validated['password'])) {
            return response()->json(["error" => "Password must contain at least 1 lowercase letter"], 422);
        }

        if (!preg_match('/[A-Z]/', $validated['password'])) {
            return response()->json(["error" => "Password must contain at least 1 uppercase letter"], 422);
        }

        $resetRequest = DB::table('password_reset_requests')
            ->where('tel', $validated['tel'])
            ->where('exp', '>', now())
            ->first();

        if (!$resetRequest) {
            return response()->json([
                'error' => 'Invalid or expired password reset request'
            ], 403);
        }

        $user = User::where('tel', $validated['tel'])->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        try {
            DB::beginTransaction();
            $user->password = Hash::make($validated['password']);
            $user->save();
            DB::table('password_reset_requests')->where('tel', $validated['tel'])->delete();
            DB::commit();
            return response()->json(['message' => 'Password reset successful'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to reset password',
                'details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
}