<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Role extends Model
{
    protected $table = "roles";

    protected $fillable = [
        'title',
        'slug',
        'description'
    ];
    public function users()
    {
        //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
       /// return $this->belongsToMany(User::class);
        return $this->hasManyThrough(
            User::class,
            RoleUser::class,
            "role_id",
            "id",
            "id",
            "user_id",

        );
    }

    public function user_roles()
    {
        return $this->hasMany(
            RoleUser::class,
            "role_id",
            "id"
        );
    }
}
