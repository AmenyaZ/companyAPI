<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{

    use HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'profile',
        'password'

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

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isUser()
    {
        return $this->role === 'user';
    }
    // public function organizations()
    // {
    //     //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
    //     return $this->belongsToMany(
    //         Organization::class,
    //         'organization_role_user',
    //         'organization_id',
    //         'user_id'
    //     );
    // }
    public function roles()
    {
        //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
        // return $this->belongsToMany(Role::class);
        return $this->hasManyThrough(
            Role::class,
            RoleUser::class,
            "user_id",
            "id",
            "id",
            "role_id"

        );
    }
    public function organizations()
    {
        //return $this->belongsToMany(Organization::class);
        return $this->hasManyThrough(
            Organization::class,
            OrganizationUser::class,
            "user_id",
            "id",
            "id",
            "organization_id"
        );
    }

    public function user_roles()
    {
        return $this->hasMany(
            RoleUser::class,
            "user_id",
            "id"
        );
    }
    public function user_organization(){
        return $this->hasMany(
            OrganizationUser::class,
            "user_id",
            "id"
        );
    }
    
}
