<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Registration request",
 *      description="Store request body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class RegistrationRequest extends FormRequest
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
     * @OA\Property(
     *      title="firstName",
     *      description="Name of the new trainer"
     * )
     *
     * @var string
     */
    protected string $firstName;

    /**
     * @OA\Property(
     *      title="lastName",
     *      description="Surname of the new trainer"
     * )
     *
     * @var string
     */
    protected string $lastName;

    /**
     * @OA\Property(
     *      title="phone",
     *      description="Phone number of the new trainer"
     * )
     *
     * @var string
     */
    protected string $phone;

    /**
     * @OA\Property(
     *      title="taxIdentityNumber",
     *      description="TaxIdentityNumber only for Training Center"
     * )
     *
     * @var integer
     */
    protected int $taxIdentityNumber;

    /**
     * @OA\Property(
     *      title="roleId",
     *      description="Role id for understandinq who is the user."
     * )
     *
     * @var integer
     */
    protected int $roleId;

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
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'phone' => 'required|string',
            'taxIdentityNumber' => 'integer',
            'roleId' => 'required|integer'
        ];
    }

}


//[
//    'required',
//    'min:8',
//    'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
//    'confirmed'
//],
