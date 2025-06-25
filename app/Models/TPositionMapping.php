<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPositionMapping extends Model
{
    use HasFactory;

    protected $table = 'TPositionMapping';
    protected $fillable = [
        'Id',
        'StaffPlayerCode',
        'Positioncode',
        'FlagMain',
        'CreateDate',
        'UpdateDate',
        'CreateBy',
        'UpdateBy',
       
    ];

    protected $primaryKey = 'Id';
    public $timestamps = false;

    public function TPositionMapping()
    {
        return $this->belongsTo(TPositionMapping::class);
    }
}
