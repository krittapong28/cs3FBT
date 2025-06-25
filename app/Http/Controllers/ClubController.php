<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\League;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;

class ClubController extends Controller
{


    public function index(Request $request)
{
    $LeagueCode = $request->ddlLeagueSearch;
    $leaguelist = League::all();

    $clublist = Club::query(); // Start a query, not data yet

    // Search-----------------------------------------------------------------------------
    if (!empty($request->ddlLeagueSearch)) {
        $clublist->where('LeagueCode', $request->ddlLeagueSearch);
    }
    if (!empty($request->LeagueName)) {
        $clublist->where('LeagueName', 'like', "%{$request->LeagueName}%");
    }
    //-----------------------------------------------------------------------------------

    $clublist = $clublist->get(); // Now execute the query!

    return view('club.index', compact('clublist', 'leaguelist','LeagueCode'));
}
    public function Club_Detail($id)
    {
        // ใช้ findOrFail($id) เพื่อค้นหาด้วย Primary Key (id) ของตาราง clubs
        // ถ้าไม่พบจะโยน Exception ซึ่งถูกจัดการโดย Laravel อยู่แล้ว
        $club = Club::findOrFail($id);

        // ตรวจสอบว่า view นี้อยู่ใน resources/views/clublist/ClubDetail.blade.php จริงๆ
        return view('club.ClubDetail', compact('club'));
    }


}
