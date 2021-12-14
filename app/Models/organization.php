<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class organization extends Model
{
    protected $fillable = [
        'legal_name',
        'physical_location',
        'year',
        'company_logo'
    ];
}
