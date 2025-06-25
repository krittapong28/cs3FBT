<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lookup extends Model
{
    use HasFactory;

    protected $table = 'MOC_M_LOOKUP'; // Specify the correct table name
    protected $fillable = [
        'Id', 
        'LookupType',
        'LookupCode',
        'LookupValue',
    ];
}
