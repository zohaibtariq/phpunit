<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $user = User::whereEmail($request->email)->first();
        if(!$user)
            return response('Incorrect details...', Response::HTTP_UNAUTHORIZED);
        $token = $user->createToken('api');
        return response([
            'token' => $token->plainTextToken
        ]);
    }
}
