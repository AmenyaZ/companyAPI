<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;
use Laravel\Passport\Token;


class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken]);
    }

    public function login(LoginRequest $request)
    {
  
        $validator = $request->validated();

        if (!Auth::attempt($validator)) {
            return response(['message' => 'Invalid Credentials']);
        }
        $user = User::where('email', $request->email)->first();

        $accessToken = $user->createToken('authToken')->accessToken;
        return response(['user' => $user,'access_token' => $accessToken, 'message' => 'Log In Succesful']);
    }
    public function logout()
    {
        $accessToken = auth()->logout();
        return response(['message' => 'You have been successfully logged out.'], 200);
    }
}
