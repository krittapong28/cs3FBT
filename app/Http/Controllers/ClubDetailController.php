<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\League;
use Carbon\Carbon;
use App\Models\Player;
use App\Models\Staff;
use App\Models\TPositionMapping;
use App\Models\MPosition;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;

class ClubDetailController extends Controller
{


public function Club_Detail($id)
{
    $club = Club::findOrFail($id);

    // 2. ดึงข้อมูลผู้เล่นที่อยู่ในสโมสรนี้
    $players = Player::query()
        ->leftJoin('tpositionmapping', 'MPlayer.PlayerCode', '=', 'tpositionmapping.StaffPlayerCode')
        ->leftJoin('mposition', function($join) {
            $join->on('tpositionmapping.Positioncode', '=', 'mposition.Positioncode')
                 ->where('mposition.FlagPlayer', 'PLAYER');
        })
        ->where('MPlayer.TeamCode', $club->TeamCode)
        ->where('tpositionmapping.FlagMain', 'Y')
        ->select('MPlayer.*', 'mposition.PositionName')
        ->get();

    // 3. ดึงข้อมูลสต๊าฟที่อยู่ในสโมสรนี้
    $staffs = Staff::query()
    ->leftJoin('tpositionmapping', 'MStaff.StaffCode', '=', 'tpositionmapping.StaffPlayerCode')
    ->leftJoin('mposition', function($join) {
        $join->on('tpositionmapping.Positioncode', '=', 'mposition.Positioncode')
             ->where('mposition.FlagPlayer', 'STAFF');
    })
    ->where('MStaff.TeamCode', $club->TeamCode)
    // ***เพิ่มเงื่อนไขนี้เพื่อกรอง FlagMain = 'Y'***
    ->where('tpositionmapping.FlagMain', 'Y') // หรือ 'y' ถ้าข้อมูลใน DB เป็นตัวเล็ก
    ->select('MStaff.*', 'mposition.PositionName', 'mposition.Positioncode', 'tpositionmapping.FlagMain')
    ->get();

    // 4. เตรียมข้อมูลเพิ่มเติมที่ต้องการแสดง
    $headCoach = $staffs->where('PositionName', 'Head Coach')->first();
    $playerCount = $players->count();
    $foreignerCount = $players->where('Nationality', '!=', 'Thai')->count();

    $totalAge = 0;
    foreach ($players as $player) {
        if ($player->DOB) {
            $birthDate = Carbon::parse($player->DOB);
            $totalAge += $birthDate->diffInYears(Carbon::now());
        }
    }
    $averageAge = $playerCount > 0 ? round($totalAge / $playerCount, 1) : 0;

    // 5. ส่งข้อมูลทั้งหมดไปยัง view
    return view('club.ClubDetail', compact('club', 'players', 'staffs', 'headCoach', 'playerCount', 'foreignerCount', 'averageAge'));
}
}