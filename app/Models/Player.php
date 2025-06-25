<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $table = 'MPlayer';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Id',
        'PlayerCode',
        'TeamCode',
        'FirstName',
        'LastName',
        'Age',
        'DOB',
        'Nationality',
        'Foot',
        'PathImage',
        'Salary',
        'ExpectedSalary',
        'FlagAgent',
        'CreateDate',
        'UpdateDate',
        'CreateBy',
        'UpdateBy',
        'Height',
        'Img',
    ];
}

