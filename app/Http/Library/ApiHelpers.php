<?php

namespace App\Http\Library;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait ApiHelpers
{
    protected function isAdmin($user): bool
    {
        if (!empty($user)) {

            if ($user->id == 1) {
                return true;
            }
        }

        return false;
    }

    protected function isUser($user): bool
    {

        if (!empty($user)) 
        {
            return true;
        }

        return false;
    }

    protected function onSuccess($data, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function onError(int $code, string $message = ''): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ], $code);
    }

    protected function roleValidationRules(): array
    {
        return [
            'title' => 'required|string',
            //'slug' => 'required|string',
            'description' => 'required|string',
        ];
    }

    // protected function userValidatedRules(): array
    // {
    //     return [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ];
    // }
}
