<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;
use Laravel\Passport\Token;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken]);
    }
    /* $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }
    

        //$user = User::find($id);
        //$accessToken = auth()->$user->createToken('authToken')->accessToken;
        //$accessToken = auth()->user()->createToken('authToken')->accessToken;
        //$accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
        */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $accessToken = $user->createToken('authToken')->accessToken;
                $response = ['user' => $user, 'access_token' => $accessToken];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }
    public function logout(Request $request, $id)
    {
        // $accessToken = auth()->user()->currentAccessToken();
        // $token= $request->user()->tokens->find($accessToken);
        // $token->revoke();
        // return response(['message' => 'You have been successfully logged out.'], 200);

        // $request->user()->currentAccessToken()->delete();
        return response(['message' => 'You have been successfully logged out.'], 200);
    }
}
