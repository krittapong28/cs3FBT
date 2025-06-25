<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class Role extends Model
{
    use HasFactory;

    protected $table = 'MOC_M_ROLE'; // Specify the correct table name
    protected $fillable = [
        'ID',
        'ROLE_CODE',
        'ROLE_NAME',
        'ROLE_DESC',
        'FLAG_ACTIVE',
        'FLAG_DELETE',
    ];
    

    // protected $casts = [
    //     'CREATE_DATE' => 'datetime:Y-m-d',
    //     'UPDATE_DATE' => 'datetime:Y-m-d',
    // ];
}
