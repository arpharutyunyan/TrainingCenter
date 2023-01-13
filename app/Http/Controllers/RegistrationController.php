<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * @OA\Post(
     ** path="/auth/register",
     *   tags={"Register"},
     *   summary="Register",
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user registration credentials",
     *    @OA\JsonContent(
     *       required={"email","password","firstName","lastName","phone","roleId"},
     *       @OA\Property(property="email", type="string", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", example="PassWord12345"),
     *       @OA\Property(property="firstName", type="string", example="Anna"),
     *       @OA\Property(property="lastName", type="string", example="Smith"),
     *       @OA\Property(property="phone", type="string", example="094415263"),
     *       @OA\Property(property="taxIdentityNumber", type="integer", example=05720125),
     *       @OA\Property(property="roleId", type="integer", example=1),
     *    ),
     * ),
     *
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Content"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *)
     **/
    public function register(RegistrationRequest $request)
    {

        try {
            $request->validated();

            $user = new User([

                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'phone' => $request->phone,
                'taxIdentityNumber' => $request->taxIdentityNumber,
                'roleId' => $request->roleId
            ]);

            $user->save();

            return response()->json(['message' => 'User has been registered.', 'data' => $user], 201);

        }catch (\Exception $exception){
//            return response()->json(['message' => 'Something went wrong please check registration fields!'], 422);
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }
}

