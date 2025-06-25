<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Duty extends Model
{
    use HasFactory;
    protected $table = 'MOC_T_DUTY_POINT';
    protected $fillable = [
        'REQUEST_CODE',
        'DUTY',
        'POINT',
        'EMP_CODE',
        'FLAG_DEFECT',
        'DEFECT_LEVEL',
        'DEFECT_RESULT',
        'FLAG_COMPLETE',
        'STATUS',
        'CREATE_DATE',
        'CREATE_BY',
        'UPDATE_DATE',
        'UPDATE_BY',
    ];

    protected $primaryKey = 'ID';
    public $timestamps = false;
}
