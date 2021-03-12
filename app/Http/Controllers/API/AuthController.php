<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);


        
        $data['password'] = Hash::make($data['password']);
        try {
            $user = User::create($data);
        } catch (Exception $e) {
            return response()->json(['message' => "Error occurred!"], 400);
            
        }
        

        // return response()->json(['message' => 'sdfdf'], 400);

        
        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);


        if (!Auth::attempt($credentials)) {
            return response(['message' => 'This User does not exist, check your details'], 400);
        }


        $user = Auth::user();

        $accessToken = $user->createToken($user->email . '-' . now())->accessToken;

        return response([
            'user' => Auth::user(),
            'access_token' => $accessToken
        ]);
    }

    public function logout(Request $request)
    {
        // $accessToken = auth()->user()->token();
        // $token= $request->user()->tokens->find($accessToken);
        // $token->revoke();

        $request->user()->token()->revoke();

        return response(['message' => 'You have been successfully logged out.'], 200);
    }
}
