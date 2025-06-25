<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\GroupSection;
use App\Models\Menu;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\EmployeeRole;
use App\Models\Job;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Site;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::getDistinctMenus(session('emp_code'));
        $GroupCode = $request->group_code;
        $employees = Employee::where('FLAG_ACTIVE', 'Y');
        $siteSearch = Site::where('FLAG_ACTIVE', 'Y')->where('FLAG_DELETE', '!=', 'Y')->get();

        $site = Site::where('GROUP_CODE', $GroupCode)->where('FLAG_ACTIVE', 'Y')->where('FLAG_DELETE', '!=', 'Y');

        // Search-----------------------------------------------------------------------------
        if ($request->has('searchSITE_NAME') && !empty($request->searchSITE_NAME)) {
            $site->where('SITE_NAME', 'like', "%{$request->searchSITE_NAME}%");
        }
        if ($request->has('SITE_ADMIN') && !empty($request->SITE_ADMIN)) {
            $site->where('SITE_ADMIN', 'like', "%{$request->SITE_ADMIN}%");
        }
        //-----------------------------------------------------------------------------------
        $site = $site
        ->orderBy('SITE_NAME','ASC')
        ->paginate(5); // 5 items per page

        $site->appends([
            'group_code' => $GroupCode,
            'searchSITE_NAME' => $request->input('searchSITE_NAME'),
            'searchSITE_ADMIN' => $request->input('searchSITE_ADMIN'),
        ]);
        $employeeSearch = Employee::orderBy('EMPLOYEE_ID', 'ASC')->where('FLAG_ACTIVE', 'Y')->paginate(15);
        $SiteFilters = Site::where('GROUP_CODE', $GroupCode)->select('SITE_CODE','SITE_NAME')->where('FLAG_ACTIVE', 'Y')->where('FLAG_DELETE', '!=', 'Y')->get();
        $GroupSites = GroupSection::where('FLAG_ACTIVE', 'Y')->where('FLAG_DELETE', '!=', 'Y')->get();
        $Organizations = Organization::select('ORG_CODE','ORG_THAI_NAME_PATH')->where('FLAG_ACTIVE', 'Y')->where('FLAG_DELETE', '!=', 'Y')
        ->orderBy('ORG_CODE')
        ->get();

        return view('group_sections.site.index', compact('site', 'menus', 'GroupSites', 'Organizations', 'SiteFilters', 'GroupCode','employees','siteSearch'));
    }

    public function SearchEmp(Request $request)
    {
        try{
            $ddlOrgCodeSearch = $request->ddlOrgCodeSearch;
            $ddlSiteSearch = $request->ddlSiteSearch;
            $txtJobSearch = $request->txtJobSearch;
            $txtEmpCodeSearch = $request->txtEmpCodeSearch;
            $txtEmpNameSearch = $request->txtEmpNameSearch;
            $searchJob = $request->ddlJobSearch;
            $empSearch = Employee::where('FLAG_ACTIVE', 'Y')->orderBy('MOC_M_EMPLOYEE.EMPLOYEE_ID', 'ASC');
            $sites = Site::select('SITE_CODE','SITE_NAME')->get();
            $jobs = Job::all();
            //Search Condition------------------------------
            if (!empty($ddlOrgCodeSearch)) {
                $empSearch->where('ORG_CODE_TH', 'LIKE', "%$ddlOrgCodeSearch%");
            }
            if (!empty($ddlSiteSearch)) {
                $empSearch->where('SITE_CODE', 'LIKE', "%$ddlSiteSearch%");
            }
            if ($searchJob) {
                $empSearch->where('MOC_M_EMPLOYEE.JOB_CODE', 'LIKE', "%$searchJob");
            }
            if (!empty($txtJobSearch)) {
                $empSearch->where('JOB_NAME', 'LIKE', "%$txtJobSearch%");
            }
            if (!empty($txtEmpCodeSearch)) {
                $empSearch->where('EMPLOYEE_CODE', 'LIKE', "%".$txtEmpCodeSearch."%");
            }
            if (!empty($txtEmpNameSearch)) {
                $empSearch->where(DB::raw("CONCAT(FIRST_NAME, ' ', LAST_NAME)"), 'LIKE', "%".$txtEmpNameSearch."%");
            }
            //----------------------------------------------

            $empSearch = $empSearch->paginate(5);

            $data = [
                'sites' => $sites,
                'jobs' => $jobs,
                'empSearch' => $empSearch,
            ];

            return response()->json($data);

        }catch (\Throwable $th) {
            Log::error('[SiteController/SearchEmp] Error occurred: ' . $th->getMessage());
        }
    }

    public function StoreSite(Request $request)
    {
        try{
            $emp_code = session('emp_code');
            $Organizations = Organization::select('SITE_CODE', 'ORG_THAI_NAME_PATH')->where('ORG_CODE', $request->ORG_CODE)->first();
            $employee = Employee::where('EMPLOYEE_ID',$request->SITE_ADMIN)->where('FLAG_ACTIVE', 'Y')->first();

            $Site = Site::create([
                'AGENCY' => $Organizations->ORG_THAI_NAME_PATH,
                'SITE_CODE' => $Organizations->SITE_CODE,
                'SITE_NAME' => $request->SITE_NAME,
                'SITE_ADMIN' => $employee->EMPLOYEE_CODE,
                'GROUP_CODE' => $request->GROUP_CODE,
                'ORG_CODE' => $request->ORG_CODE,
                'CREATE_DATE' => Carbon::now(),
                'CREATE_BY' => $emp_code,
                'FLAG_ACTIVE' => "Y",
                'FLAG_DELETE' => "N",
            ]);

            // SITEADMIN
            EmployeeRole::updateOrCreate(
                [
                    'EMPLOYEE_CODE' => $employee->EMPLOYEE_CODE,
                    'ROLE_CODE' => 'SITEADMIN',
                    'SITE_CODE' => $Organizations->SITE_CODE,

                ],
                [
                    'EMPLOYEE_CODE' => $employee->EMPLOYEE_CODE,
                    'ROLE_CODE' => 'SITEADMIN',
                    'SITE_CODE' => $Organizations->SITE_CODE,
                    'UPDATE_DATE' => Carbon::now(),
                    'UPDATE_BY' => $emp_code,
                ]
            );

            if ($Site) {
                $alertData = [
                    'title' => 'บันทึกสำเร็จ',
                    'message' => 'ทำการบันทึกข้อมูลสำเร็จแล้ว',
                    'icon' => 'success',
                ];
            }

        }catch (\Throwable $th) {
            Log::error('[SiteController/StoreSite] Error occurred: ' . $th->getMessage());
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการบันทึก',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาตรวจสอบข้อผิดพลาด',
                'icon' => 'error',
            ];
        }
        
        session()->flash('alert', $alertData);
        return redirect()->back();
    }

    public function EditSite($id)
    {
        $Site = Site::where('ID', $id)->with('emp')->first();

        return response()->json($Site);
    }

    public function UpdateSite(Request $request)
    {
        try{
            $emp_code = session('emp_code');
            $Organization = Organization::select('SITE_CODE', 'ORG_THAI_NAME_PATH')->where('ORG_CODE', $request->updateORG_CODE)->first();
            $Site = Site::find($request->ID);

            // SITEADMIN
            EmployeeRole::updateOrCreate(
                [
                    'EMPLOYEE_CODE' => $Site->SITE_ADMIN,
                    'ROLE_CODE' => 'SITEADMIN',
                    'SITE_CODE' => $Site->SITE_CODE,
                ],
                [
                    'EMPLOYEE_CODE' => $request->SITE_ADMIN,
                    'ROLE_CODE' => 'SITEADMIN',
                    'SITE_CODE' => $Site->SITE_CODE,
                    'UPDATE_DATE' => Carbon::now(),
                    'UPDATE_BY' => $emp_code,
                ]
            );

            $Site->AGENCY = $Organization->ORG_THAI_NAME_PATH;
            $Site->SITE_CODE = $Organization->SITE_CODE;
            $Site->SITE_ADMIN = $request->SITE_ADMIN;
            $Site->SITE_NAME = $request->has('updateSITE_NAME') ? $request->updateSITE_NAME : null;
            $Site->GROUP_CODE = $request->updateGROUP_NAME;
            $Site->ORG_CODE = $request->updateORG_CODE;
            $Site->UPDATE_DATE = Carbon::now();
            $Site->UPDATE_BY = $emp_code;
            $rs_save = $Site->save();

            

            if ($rs_save) {
                $alertData = [
                    'title' => 'บันทึกสำเร็จ',
                    'message' => 'ทำการบันทึกข้อมูลสำเร็จแล้ว',
                    'icon' => 'success',
                ];
            }

        }catch (\Throwable $th) {
            Log::error('[SiteController/UpdateSite] Error occurred: ' . $th->getMessage());
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการบันทึก',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาตรวจสอบข้อผิดพลาด',
                'icon' => 'error',
            ];
        }
        
        session()->flash('alert', $alertData);
        return redirect()->back();
    }
}
