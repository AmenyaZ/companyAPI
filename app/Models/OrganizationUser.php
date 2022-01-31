<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationUser extends Model
{
    protected $table = "organization_user";

    protected $fillable = [
        'organization_id',
        'user_id'
    ];
}
