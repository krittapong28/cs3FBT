<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $table = 'MTeam';
    protected $fillable = [
        'Id',
        'TeamCode',
        'TeamName',
        'StadiumName',
        'LeagueCode',
        'CityCode',
        'Img',
        'CreateDate',
        'UpdateDate',
        'CreateBy',
        'UpdateBy',
    ];

    protected $primaryKey = 'Id';
    public $timestamps = false;

    public function leagues()
    {
        return $this->belongsTo(League::class);
    }
}
