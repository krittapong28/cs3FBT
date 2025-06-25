<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    protected $table = 'MLeague';
    protected $fillable = [
        'Id',
        'LeagueName',
        'LeagueCode',
        'PathImage',
        'CreateDate',
        'UpdateDate',
        'CreateBy',
        'UpdateBy',
    ];

    protected $primaryKey = 'Id';
    public $timestamps = false;

    public function teams()
    {
        return $this->hasMany(Club::class);
    }
}
