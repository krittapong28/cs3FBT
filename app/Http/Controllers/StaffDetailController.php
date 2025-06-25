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
use App\Models\Staff;
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

class StaffDetailController extends Controller
{
    public function index(Request $request)
    {
      
        // return view('Staff.StaffDetails.index', compact('player'));
    }
     
public function detailx($id)
{
    $Staff = Staff::findOrFail($id);
    $Position = TPositionMapping::where('StaffPlayerCode', $Staff['StaffCode'])->get(['Positioncode', 'FlagMain']);
    // ตัวแปรจริง ไม่ใส่เครื่องหมาย ''
    // dd($Staff); 
    return view('Staff.StaffDetails', compact('Staff','Position'));
}


//   dd($Position);
//    exit();
}