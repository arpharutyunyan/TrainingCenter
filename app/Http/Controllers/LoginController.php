<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     ** path="/auth/login",
     *   tags={"Login"},
     *   summary="Login",
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", example="PassWord12345"),
     *    ),
     * ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     * *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *)
     **/
    public function login(LoginRequest $request){

        $request->validated();

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

//        return response()->json(['message' => 'You are logged in!', 'data' => Auth::user()], 200);
        return response()->json(['message' => 'You are logged in!', 'data' => $user, 'jwt' => $token], 200);
    }
}
