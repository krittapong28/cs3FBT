<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Club;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $LeagueCode = $request->LeagueCode;

        $clublist = Club::all();

        // Search-----------------------------------------------------------------------------
        if ($request->has('LeagueCode') && !empty($request->LeagueCode)) {
            $clublist->where('LeagueCode', 'like', "%{$request->LeagueCode}%");
        }
        if ($request->has('LeagueName') && !empty($request->LeagueName)) {
            $clublist->where('LeagueName', 'like', "%{$request->LeagueName}%");
        }
        //-----------------------------------------------------------------------------------
        $clublist = $clublist
        ->orderBy('LeagueName','ASC')
        ->paginate(5); // 5 items per page

        $clublist->appends([
            'group_code' => $GroupCode,
            'searchLeagueCode' => $request->input('LeagueCode'),
            'searchLeagueName' => $request->input('LeagueName'),
        ]);

        return view('club.index', compact('clublist'));
    }

    // public function SearchEmp(Request $request)
    // {
    //     try{
    //         $ddlOrgCodeSearch = $request->ddlOrgCodeSearch;
    //         $ddlSiteSearch = $request->ddlSiteSearch;
    //         $txtJobSearch = $request->txtJobSearch;
    //         $txtEmpCodeSearch = $request->txtEmpCodeSearch;
    //         $txtEmpNameSearch = $request->txtEmpNameSearch;
    //         $searchJob = $request->ddlJobSearch;
    //         $empSearch = Employee::where('FLAG_ACTIVE', 'Y')->orderBy('MOC_M_EMPLOYEE.EMPLOYEE_ID', 'ASC');
    //         $sites = Site::select('SITE_CODE','SITE_NAME')->get();
    //         $jobs = Job::all();
    //         //Search Condition------------------------------
    //         if (!empty($ddlOrgCodeSearch)) {
    //             $empSearch->where('ORG_CODE_TH', 'LIKE', "%$ddlOrgCodeSearch%");
    //         }
    //         if (!empty($ddlSiteSearch)) {
    //             $empSearch->where('SITE_CODE', 'LIKE', "%$ddlSiteSearch%");
    //         }
    //         if ($searchJob) {
    //             $empSearch->where('MOC_M_EMPLOYEE.JOB_CODE', 'LIKE', "%$searchJob");
    //         }
    //         if (!empty($txtJobSearch)) {
    //             $empSearch->where('JOB_NAME', 'LIKE', "%$txtJobSearch%");
    //         }
    //         if (!empty($txtEmpCodeSearch)) {
    //             $empSearch->where('EMPLOYEE_CODE', 'LIKE', "%".$txtEmpCodeSearch."%");
    //         }
    //         if (!empty($txtEmpNameSearch)) {
    //             $empSearch->where(DB::raw("CONCAT(FIRST_NAME, ' ', LAST_NAME)"), 'LIKE', "%".$txtEmpNameSearch."%");
    //         }
    //         //----------------------------------------------

    //         $empSearch = $empSearch->paginate(5);

    //         $data = [
    //             'sites' => $sites,
    //             'jobs' => $jobs,
    //             'empSearch' => $empSearch,
    //         ];

    //         return response()->json($data);

    //     }catch (\Throwable $th) {
    //         Log::error('[SiteController/SearchEmp] Error occurred: ' . $th->getMessage());
    //     }
    // }
}
