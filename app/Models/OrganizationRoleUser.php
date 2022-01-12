<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationRoleUser extends Model
{

    public $table = 'organization_role_user';


    protected $fillable = [
        'user_id',
        'organization_id',
        'role_id'
    ];
}
