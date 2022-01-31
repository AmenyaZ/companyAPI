<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'legal_name',
        'physical_location',
        'year',
        'company_logo'
    ];
    public function users()
    {
        //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
        // return $this->belongsToMany(User::class);
        return $this->hasManyThrough(
            User::class,
            OrganizationUser::class,
            "organization_id",
            "id",
            "id",
            "user_id",
        );
    }
    public function user_organizations(){
        return $this->hasMany(
            OrganizationUser::class,
            "organization_id",
            "id",
        );
    }
}
