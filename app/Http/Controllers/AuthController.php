<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Arr;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register (RegisterRequest $request) {
        $validate = $request->validated();

        $data['name'] = Arr::pull($validate, 'name');
        $data['email'] = Arr::pull($validate, 'email');
        $data['password'] = Arr::pull($validate, 'password');

        $user = User::create($data);

        $credentials = ['email' => $user->email, 'password' => $data['password']];
        return $this->login($credentials);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function login(array $credentials = null)
    {

        $request['password'] = request('password');
        $request['user'] = request('user');
        $data['password'] = Arr::pull( $request, 'password');

        if(filter_var($request['user'], FILTER_VALIDATE_EMAIL)) {
            $data['email'] = Arr::pull($request, 'user');
        }else {
            $data['name'] = Arr::pull($request, 'user');
        }

        $attempt = !empty($credentials)?$credentials:$data;

        if (! $token = auth('api')->attempt($attempt)) {
            return responseJson([],null,'Unauthorized', 401);
        }

        return responseJson(auth('api')->user(), $token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function me()
    {
        return responseJson(auth('api')->user()->only('id', 'name', 'email'));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function logout()
    {
        auth('api')->logout();

        return responseJson([],null,'Successfully logged out', 200);
    }

}
