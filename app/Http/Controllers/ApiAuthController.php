<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function login(LoginRequest $request){
        // Check apakah data user yang ingin diinput ada
        $user = User::where('username', $request->username)->first();

        // Check password
        if(!$user|| !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'user atau password salah'
            ],401);
        }

        // generate token
        $token = $user->createToken('token')->plainTextToken;

        // return response()->json([
        //     'message' => 'success login',
        //     'user' => $user,
        //     'token' => $token,
        // ],200);

        return new LoginResource([
            'message' => 'success login',
            'user' => $user,
            'token' => $token,
        ],200);
    }
    public function logout(Request $request){
        // Hapus
        $request->user()->tokens()->delete();

        // Response
        return response()->noContent();
    }
}
