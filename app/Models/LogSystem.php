<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSystem extends Model
{
    use HasFactory;

    protected $table = 'MOC_L_SYSTEM_LOG'; // Specify the correct table name
    protected $fillable = [
        'LOG_CODE',
        'LOG_LEVEL',
        'LOG_DESC',
        'CREATE_DATE',
        'CREATE_BY',
    ];

    protected $primaryKey = 'ID';
    public $timestamps = false;
}
