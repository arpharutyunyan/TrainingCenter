<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Login request",
 *      description="Login request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class LoginRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="email",
     *      description="email of the new trainer"
     * )
     *
     * @var string
     */
    protected string $email;

    /**
     * @OA\Property(
     *      title="password",
     *      description="Password, the length must be min 8."
     * )
     *
     * @var string
     */
    protected string $password;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required|string'
        ];
    }

}
