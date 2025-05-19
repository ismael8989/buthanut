<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class VerificationController extends Controller
{
    static public function sendVN($tel, $verification_number) {
        $verification_info = [
            'tel' => $tel,
            'verification_number' => Hash::make($verification_number),
            'exp' => now()->addMinutes((int) env('VERIFICATION_EXPIRY_MINUTES'))
        ];

        $response = Http::post(env('MESSAGE_API'), [
            'tel' => $tel,
            'verification_number' => $verification_number
        ]);

        if (!$response->successful()) {            
            return response()->json([
                'error' => 'Failed to send verification message.',
                'details' => $response->json()
            ], $response->status());
        }
        
        try {
            DB::beginTransaction();
            DB::table('verification_numbers')->where('tel', $tel)->delete();
            DB::table('verification_numbers')->insert($verification_info);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => "Something went wrong, please request a new verification number \n".$e,
            ], 500);
        }

        return response()->json(['message' => 'Verification number sent and saved.']);
    }

    public function sendVerificationNumber(Request $request) {
       return $this->sendVN($request->tel, random_int(100000, 999999));
    }

    public function checkVerificationNumber(Request $request) {
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
            DB::table('tel_numbers')->updateOrInsert(
                ['tel' => $tel],
                ['verified' => true]
            );
            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();            
            return response()->json([
                'error' => "An error has occurred while verifying your number",
                'details' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }

        return response()->json([
            'message' => "Verification was successful"
        ], 200);
    }
}