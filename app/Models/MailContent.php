<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailContent extends Model
{
    use HasFactory;

    protected $table = 'MOC_M_MAIL';
    protected $fillable = [
        'ID',
        'MAIL_CODE',
        'MAIL_SUBJECT',
        'MAIL_BODY',
        'REMARK',
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
