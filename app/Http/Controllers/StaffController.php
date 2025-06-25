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


class StaffController extends Controller
{
    public function index(Request $request)
    {
        $selectedPositionCode = $request->ddlPlayerSearch;
        $selectedAge = $request->ddlAgeSearch;
        $salarymin = $request->ddlsalaryminSearch;
        $salarymax = $request->ddlsalarymaxSearch;
        
        $Positionlist = MPosition::where('FlagPlayer', 'STAFF')->get();
        $playerlist = Staff::query();

        if (!empty($selectedPositionCode)) {
            $validPosition = MPosition::where('FlagPlayer', 'STAFF')
                ->where('Positioncode', $selectedPositionCode)
                ->exists();

            if ($validPosition) {
                $playerCodes = TPositionMapping::where('Positioncode', $selectedPositionCode)
                    ->pluck('StaffPlayerCode');
                $playerlist->whereIn('StaffCode', $playerCodes);
            } else {
                $playerlist->whereRaw('1=0');
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

        if (!empty($selectedAge)) {
            $now = Carbon::now();
            $minDOB = null;
            $maxDOB = null;

            switch ($selectedAge) {
                case '1': $maxAge = 14; $minAge = 0; break;
                case '2': $maxAge = 15; $minAge = 0; break;
                case '3': $maxAge = 16; $minAge = 0; break;
                case '4': $maxAge = 17; $minAge = 0; break;
                case '5': $maxAge = 18; $minAge = 0; break;
                case '6': $maxAge = 19; $minAge = 0; break;
                case '7': $maxAge = 20; $minAge = 0; break;
                case '8': $maxAge = 21; $minAge = 0; break;
                case '9': $maxAge = 22; $minAge = 0; break;
                case '10': $minAge = 23; $maxAge = 30; break;
                case '11': $minAge = 31; $maxAge = 36; break;
                default: $minAge = null; $maxAge = null;
            }

            if ($minAge !== null && $maxAge !== null) {
                $maxDOB = $now->copy()->subYears($minAge)->endOfDay()->format('Y-m-d');
                $minDOB = $now->copy()->subYears($maxAge + 1)->startOfDay()->format('Y-m-d');
                $playerlist->whereBetween('DOB', [$minDOB, $maxDOB]);
            }
        }

        $playerlist = $playerlist->get();
        return view('Staff.index', compact('playerlist', 'selectedPositionCode', 'Positionlist', 'selectedAge'));
    }

    public function detail($id)
    {
        $Staff = Staff::where('Id', $id)->firstOrFail();
        
        $Position = TPositionMapping::where('StaffPlayerCode', $Staff->StaffCode)
            ->join('mposition', 'tpositionmapping.Positioncode', '=', 'mposition.Positioncode')
            ->where('mposition.FlagPlayer', 'STAFF')
            ->select('tpositionmapping.*', 'mposition.PositionName', 'mposition.Positioncode')
            ->get();

        return view('Staff.StaffDetails', compact('Staff', 'Position'));
    }
}