<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'MOC_M_MENU'; // Specify the correct table name
    protected $fillable = ['ID', 'MENU_NAME'];

    public static function getDistinctMenus($empCode)
    {
        // Check if $empCode has exactly 6 characters
        if (strlen($empCode) === 6) {
            // Concatenate "00" before $empCode
            $empCode = '00' . $empCode;
        }
        return self::distinct('MOC_M_MENU.ID')
            ->select('MOC_M_MENU.*')
            ->leftJoin('MOC_T_ROLE_MENU', 'MOC_T_ROLE_MENU.MENU_ID', '=', 'MOC_M_MENU.ID')
            ->leftJoin('MOC_T_EMPLOYEE_ROLE', 'MOC_T_EMPLOYEE_ROLE.ROLE_CODE', '=', 'MOC_T_ROLE_MENU.ROLE_CODE')
            ->where('MOC_T_EMPLOYEE_ROLE.EMPLOYEE_CODE', $empCode)
            ->get();
    }
}
