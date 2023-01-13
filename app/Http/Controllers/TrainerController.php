<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class TrainerController extends Controller
{
    /**
     * @OA\Get(
     *      path="/trainer",
     *      tags={"TrainingCenter"},
     *      summary="Get list of trainers",
     *      description="Returns list of trainers",
     *      security={ {"sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(@OA\Schema(
     *              type="object",
     *              example={"email": "ex@gmail.com", "fisrtName": "Name", "lastName":"surname", "phone":"123456", "roleId":1}
     *          ))
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="Trainer not found."
     *      )
     * )
     */
    public function index()
    {
        $users = User::getTrainers();

        if(!$users){
            return response()->json(['message' => "Trainer not found."], 400);
        }

        return response()->json(['data' => $users, 'messages' => 'Successful operation'], 200);
    }

    /**
     * @OA\Post(
     *   path="/trainer",
     *   tags={"TrainingCenter"},
     *   summary="Create",
     *   description="Returns created trainers data",
     *   security={ {"sanctum": {} }},
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
     *   @OA\Response(
     *       response=403,
     *       description="Forbidden"
     *   )
     * )
     */
    public function store(RegistrationRequest $request)
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
            return response()->json(['message' => 'Trainer has been created.', 'data' => $user], 200);

        }catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    /**
     * @OA\Put(
     *      path="/trainer/{id}",
     *      tags={"TrainingCenter"},
     *      summary="Update existing trainer",
     *      description="Returns updated trainer data",
     *      security={ {"sanctum": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Trainer id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *     @OA\RequestBody(
     *    required=true,
     *    description="Pass user registration credentials",
     *    @OA\JsonContent(
     *       required={"email","firstName","lastName","phone","roleId"},
     *       @OA\Property(property="email", type="string", example="user1@mail.com"),
     *       @OA\Property(property="firstName", type="string", example="Anna"),
     *       @OA\Property(property="lastName", type="string", example="Smith"),
     *       @OA\Property(property="phone", type="string", example="094415263"),
     *       @OA\Property(property="taxIdentityNumber", type="integer", example=05720125),
     *       @OA\Property(property="roleId", type="integer", example=1),
     *    ),
     * ),
     *
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *           @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function update(UpdateRequest $request, $id)
    {
        $request->validated();

        try {
            // check if updated email is already registered, but not for this user
            $userId = User::select('id')->where('email', $request->email)->get()[0]['id'];

            if($userId != $id){
                return response()->json(['message' => "The email has already been taken."], 422);
            }
        }catch (\ErrorException $exception){
            // if email not found get()[0] will give "Undefined array key"
        }

        $user = User::where('id', $id)
            ->update([
                'email' => $request->email,
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'phone' => $request->phone,
                'roleId' => $request->roleId
            ]);

        if(!$user){
            return response()->json(['message' => "Check trainer id"]);
        }
        return response()->json(['message' => "Trainer has been updated.", 'data' => (User::where('id', $id)->get())], 202);
    }

    /**
     * @OA\Delete(
     *      path="/trainer/{id}",
     *      tags={"TrainingCenter"},
     *      summary="Delete existing trainer",
     *      description="Deletes a record",
     *      security={ {"sanctum": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Trainer id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),

     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent(),
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy($id)
    {
        $deleted = User::where('id', $id)->delete();

        if(!$deleted){
            return response()->json(['message' => "Check trainer id"], 404);
        }

        return response()->json(['message' => "Trainer has been deleted."], 204);

    }

    /**
     * @OA\Get(
     *      path="/period",
     *      tags={"TrainingCenter"},
     *      summary="Get list of trainers with created data",
     *      description="Returns list of trainers",
     *      security={ {"sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(@OA\Schema(
     *              type="object",
     *              example={"email": "ex@gmail.com", "fisrtName": "Name"}
     *          ))
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="Trainer not found."
     *      )
     * )
     */
    public function listWithCreatedPeriod()
    {
        $users = User::getTrainers_with_created_period();

        if(!$users){
            return response()->json(['message' => "Trainer not found."], 400);
        }

        return response()->json(['data' => $users]);
    }

}
