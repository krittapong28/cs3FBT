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

class PlayerDetailController extends Controller
{
    public function index(Request $request)
    {
        return view('player-details.index', compact('player'));
    }
     
public function detail($id)
{
    $player = Player::findOrFail($id);
     $Position = TPositionMapping::where('StaffPlayerCode', $player['PlayerCode'])
           ->get(['Positioncode', 'FlagMain']);
   
    return view('player.PlayerDetails', compact('player','Position'));
}


//   dd($Position);
//    exit();
}