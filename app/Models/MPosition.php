<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MPosition extends Model
{
    use HasFactory;

    protected $table = 'MPosition';
    protected $fillable = [
        'Id',
        'Positioncode',
        'PositionName',
        'FlagMain',
        'CreateDate',
        'UpdateDate',
        'CreateBy',
        'UpdateBy',
       
    ];

    protected $primaryKey = 'Id';
    public $timestamps = false;

    public function MPosition()
    {
        return $this->belongsTo(MPosition::class);
    }
}
