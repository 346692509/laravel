<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['name', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => '用户名错误'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {

        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 720
        ]);
    }
    ///////
//    public function login(Request $request)
//    {
//        $credentials = $request->only('email', 'password');
//        if ( ! $token = JWTAuth::attempt($credentials)) {
//            return response([
//                'status' => 'error',
//                'error' => 'invalid.credentials',
//                'msg' => 'Invalid Credentials.'
//            ], 400);
//        }
//        return response(['status' => 'success'])
//            ->header('Authorization', $token);
//    }
//    public function user(Request $request)
//    {
//        $user = User::find(Auth::user()->id);
//        return response([
//            'status' => 'success',
//            'data' => $user
//        ]);
//    }
//    public function refresh()
//    {
//        return response([
//            'status' => 'success'
//        ]);
//    }
}
