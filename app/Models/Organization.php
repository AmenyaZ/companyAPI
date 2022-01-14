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
    // public function users()
    // {
    //     //return $this->belongsToMany(RelatedModel, pivot_table_name, foreign_key_of_current_model_in_pivot_table, foreign_key_of_other_model_in_pivot_table);
    //     return $this->belongsToMany(User::class,'organization_role_user');
    // }
}
