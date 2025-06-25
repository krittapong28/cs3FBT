<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Menu;
use App\Models\RequestStatus;
use App\Models\Requests;
use App\Models\Site;
use App\Models\Organization;
use App\Models\Player;
use App\Models\TPositionMapping;
use App\Models\MPosition;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;


class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $selectedPositionCode = $request->ddlPlayerSearch; // รับค่าจาก dropdown
        $selectedAge = $request->ddlAgeSearch;
      $salarymin= $request->ddlsalaryminSearch;
      $salarymax= $request->ddlsalarymaxSearch;
        $Positionlist = MPosition::where('FlagPlayer', 'PLAYER')->get();
       
        // เตรียม query นักเตะ
        $playerlist = Player::query();

        // ถ้ามีการเลือกตำแหน่ง
      
if (!empty($selectedPositionCode)) {
    // ตรวจสอบว่า selectedPositionCode อยู่ในตำแหน่งที่เป็น PLAYER
    $validPosition = MPosition::where('FlagPlayer', 'PLAYER')
        ->where('Positioncode', $selectedPositionCode)
        ->exists();

    if ($validPosition) {
        // ดึง PlayerCodes จาก mapping table
        $playerCodes = TPositionMapping::where('Positioncode', $selectedPositionCode)
            ->pluck('StaffPlayerCode');

        // ค้นหานักเตะ
        $playerlist->whereIn('PlayerCode', $playerCodes);
    } else {
        // ป้องกันไม่ให้ดึงนักเตะที่ตำแหน่งไม่ใช่ PLAYER
        $playerlist->whereRaw('1=0'); // ไม่เจอข้อมูลเลย
    }
}




           if ($salarymin !== null || $salarymax !== null) {
                  $playerlist->where(function ($query) use ($salarymin, $salarymax) {
            if ($salarymin !== null && $salarymax !== null) {
                $query->whereBetween('Salary', [$salarymin, $salarymax]);
            } elseif ($salarymin !== null) {
                $query->where('Salary', '>=', $salarymin);
            } elseif ($salarymax !== null) {
                $query->where('Salary', '<=', $salarymax);
            }
        });
    }
    
     

        // ถ้ามีการเลือกช่วงอายุ
        if (!empty($selectedAge)) {
            $now = Carbon::now();
            $minDOB = null;
            $maxDOB = null;

            switch ($selectedAge) {
                case '1':
                    $maxAge = 14; // อายุไม่เกิน 15 ปี หมายถึง 14 ปีและน้อยกว่า
                    $minAge = 0;
                    break;
                case '2':
                    $maxAge = 15; // อายุไม่เกิน 16 ปี หมายถึง 15 ปีและน้อยกว่า
                    $minAge = 0;
                    break;
                case '3':
                    $maxAge = 16; // อายุไม่เกิน 17 ปี หมายถึง 16 ปีและน้อยกว่า
                    $minAge = 0;
                    break;
                case '4':
                    $maxAge = 17; // อายุไม่เกิน 18 ปี หมายถึง 17 ปีและน้อยกว่า
                    $minAge = 0;
                    break;
                case '5':
                    $maxAge = 18; // อายุไม่เกิน 19 ปี หมายถึง 18 ปีและน้อยกว่า
                    $minAge = 0;
                    break;
                case '6':
                    $maxAge = 19; // อายุไม่เกิน 20 ปี หมายถึง 19 ปีและน้อยกว่า
                    $minAge = 0;
                    break;
                case '7':
                    $maxAge = 20; // อายุไม่เกิน 21 ปี หมายถึง 20 ปีและน้อยกว่า
                    $minAge = 0;
                    break;
                case '8':
                    $maxAge = 21; // อายุไม่เกิน 22 ปี หมายถึง 21 ปีและน้อยกว่า
                    $minAge = 0;
                    break;
                case '9':
                    $maxAge = 22; // อายุไม่เกิน 23 ปี หมายถึง 22 ปีและน้อยกว่า
                    $minAge = 0;
                    break;
                case '10':
                    $minAge = 23;
                    $maxAge = 30;
                    break;
                case '11':
                    $minAge = 31;
                    $maxAge = 36;
                    break;
                default:
                    $minAge = null;
                    $maxAge = null;
            }

            if ($minAge !== null && $maxAge !== null) {
                $maxDOB = $now->copy()->subYears($minAge)->endOfDay()->format('Y-m-d ');
                $minDOB = $now->copy()->subYears($maxAge + 1)->startOfDay()->format('Y-m-d ');
                // เพื่อให้รวมอายุพอดี เช่นอายุ 15 = วันเกิดก่อนหรือเท่ากับ 12-05-2010

                $playerlist->whereBetween('DOB', [$minDOB, $maxDOB]);
                Session::flash('alert', 'คุณได้เลือกช่วงอายุ: ' . ($minAge == 0 ? 'ไม่เกิน ' . $maxAge : $minAge . ' ถึง ' . $maxAge) . ' ปี');
            }
        }

        // ดึงข้อมูลนักเตะจริงจาก DB
        $playerlist = $playerlist->get();
    
        $selectedPositionCode = request('ddlPlayerSearch'); 
        return view('Player.index', compact('playerlist', 'selectedPositionCode', 'Positionlist', 'selectedAge'));
    }
   public function detail($id)
{
    $player = Player::where('PlayerCode', $id)->firstOrFail();    
    return view('player.PlayerDetails', compact('player'));
}
}