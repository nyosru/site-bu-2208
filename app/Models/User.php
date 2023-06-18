<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



 /*
 * @OA\Definition(
 *  definition="Post",
 *  @OA\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @OA\Property(
 *      property="title",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="text",
 *      type="string"
 *  )
 * )
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        'password' => 'hashed',
    ];



    /*
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var bigInteger
     */
    private $id;
    /*
     * @OA\Property(
     *      title="Alias",
     *      description="Псевдоним канала",
     *      example="email"
     * )
     *
     * @var string
     */
    private $alias;
    /*
     * @OA\Property(
     *      title="Name",
     *      description="Название канала",
     *      example="E-mail"
     * )
     *
     * @var string
     */
    private $name;
    /*
     * @OA\Property(
     *      title="Is Active",
     *      description="Активный канала",
     *      example="true"
     * )
     *
     * @var boolean
     */
    private $is_active;
    /*
     * @OA\Property(
     *      title="Default Driver Alias",
     *      description="Драйвер по умолчанию на этом канале",
     *      example="mailtrap"h
     * )
     *
     * @var string
     */
    private $default_driver_alias;
    /*
     * @OA\Property(
     *     title="Created at",
     *     description="Created at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     */
    private $created_at;
    /*
     * @OA\Property(
     *     title="Updated at",
     *     description="Updated at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     */

}
