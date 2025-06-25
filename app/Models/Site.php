<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $table = 'MOC_M_SITE';
    protected $fillable = [
        'ID',
        'AGENCY',
        'SITE_CODE',
        'SITE_NAME',
        'SITE_ADMIN',
        'GROUP_CODE',
        'ORG_CODE',
        'CREATE_DATE',
        'CREATE_BY',
        'UPDATE_DATE',
        'UPDATE_BY',
        'FLAG_DELETE',
        'FLAG_ACTIVE',
    ];

    protected $primaryKey = 'ID';
    public $timestamps = false;

    public function emp()
    {
        return $this->hasOne(Employee::class,'EMPLOYEE_CODE','SITE_ADMIN');
    }
}
