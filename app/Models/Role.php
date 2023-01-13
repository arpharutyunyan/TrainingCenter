<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Role",
 *     description="Role model",
 *     @OA\Xml(
 *         name="Role"
 *     )
 * )
 */
class Role extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     *      title="name",
     *      description="Title of the role"
     * )
     *
     * @var string
     */
    protected string $name;

    //relations with users table
    public function users(){
        return $this->hasMany(User::class);
    }
}
