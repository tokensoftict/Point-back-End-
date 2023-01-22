<?php

namespace Modules\Auth\Http\Controllers;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Requests\UserLoginRequest;
use Modules\Auth\Http\Requests\UserRegistrationRequest;
use Modules\Auth\Transformers\AuthCollection;

class AuthController extends Controller
{
    use RespondsWithHttpStatus;

    public function auth(UserLoginRequest $request) : JsonResponse
    {
        if(!auth()->attempt($request->only(['email', 'password']))){
            return  $this->failure(
                "Email & Password does not match with our record.",
                [
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ]

            );
        }


        $user = User::where('email', $request->email)->first();

        return $this->success(
            "User Logged In Successfully",
            new AuthCollection($user)
        );

    }


    public function register(UserRegistrationRequest $request) : JsonResponse
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return $this->success("User added successfully",new AuthCollection($user));

    }


    public function logout() : JsonResponse {

        auth('sanctum')->user()->currentAccessToken()->delete();

        return $this->success("Log out was successful",['status'=>true]);
    }

}
