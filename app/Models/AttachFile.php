<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttachFile extends Model
{
    use HasFactory;

    protected $table = 'MOC_T_ATTACH_FILE'; // Specify the correct table name
    protected $fillable = [
        'REQUEST_CODE',
        'STEP',
        'ITEM_CODE',
        'COMMENT_ID',
        'EMPLOYEE_CODE',
        'ITEM_NO',
        'DUTY_POINT_ID',
        'FILE_NAME_UPLOAD',
        'FILE_NAME_GEN',
        'FILE_SIZE',
        'FILE_PATH',
        'CREATE_DATE',
        'CREATE_BY',
        'UPDATE_DATE',
        'UPDATE_BY',
        'FLAG_ACTIVE',
        'FLAG_DELETE',
    ];

    protected $primaryKey = 'ID';
    public $timestamps = false;
}
