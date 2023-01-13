<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @OA\Property(
     *      title="email",
     *      description="email of the new trainer"
     * )
     *
     * @var string
     */

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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'firstName',
        'lastName',
        'phone',
        'taxIdentityNumber',
        'roleId',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //relations with roles table
    public function role(){
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the Trainers filtering User table.
     *
     * @return array<string, mixed>
     */
    public static function getTrainers()
    {
        $roleId = Role::where('name', 'trainer')->get()[0]['id'];
        $users = User::where('roleId', $roleId)->get();
        return $users;
    }

    /**
     * Get the Trainers list with period of creation․
     *
     * @return array<string, mixed>
     */
    public static function getTrainers_with_created_period()
    {

        $users = self::getTrainers();

        foreach ($users as $user) {
            $date = Carbon::parse($user['created_at']);   //->format('Y-m-d H:i:s');
            $now = Carbon::now();
            $date = $date->diffForHumans();
//            $result = Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffForHumans();
            $user['created_period'] = $date;
        }

        return $users;
    }
}
