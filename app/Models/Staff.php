<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'MStaff';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Id',
        'StaffCode', 
        'FirstName',
        'LastName',
        'DOB',  
        'Nationality', 
        'PathImage',
        'Salary',
        'ExpectedSalary',
        'FlagAgent',
        'CreateDate',
        'UpdateDate',
        'CreateBy',
        'UpdateBy', 
        'Img',
    ];
}

