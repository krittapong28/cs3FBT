<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Models\GroupSection;
use App\Models\Employee;
use App\Models\EmployeeRole;
use App\Models\Role;
use App\Models\Site;
use App\Models\Job;
use App\Models\Menu;
use App\Models\M_Organization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function Index(Request $request)
    {
        $menus = Menu::getDistinctMenus(session('emp_code'));
        $jobs = Job::paginate(5);
        $roles = Role::where('FLAG_ACTIVE', 'Y');
        $sites = Site::where('FLAG_ACTIVE', 'Y')->where('FLAG_DELETE', '!=', 'Y')->get();
        $empRole = EmployeeRole::all();
        $employees = Employee::orderBy('EMPLOYEE_ID', 'ASC')->where('FLAG_ACTIVE', 'Y')->where('FLAG_DELETE', '!=', 'Y')->paginate(10);
        $employeeSearch = Employee::orderBy('EMPLOYEE_ID', 'ASC')->paginate(15);
        
        if ($request->ajax()) {
            if ($request->input('type') === 'employeeSearch') {
                return view('partials.employee_search_table', compact('empRole','employees', 'employeeSearch', 'menus', 'jobs', 'roles', 'sites'))->render();
            } elseif ($request->input('type') === 'roles') {
                return view('partials.role_search_table', compact('empRole','employees', 'employeeSearch', 'menus', 'jobs', 'roles', 'sites'))->render();
            }
        }
        return view('employee.index', compact('empRole','employees', 'employeeSearch', 'menus', 'jobs', 'roles', 'sites'));
    }

    public function Search(Request $request)
    {
        $menus = Menu::getDistinctMenus(session('emp_code'));
        $jobs = Job::paginate(5);
        $roles = Role::where('FLAG_ACTIVE', 'Y');
        $sites = Site::where('FLAG_ACTIVE', 'Y')->where('FLAG_DELETE', '!=', 'Y')->get();
        $empRole = EmployeeRole::all();
        $uniqueOrg = M_Organization::all();
        $searchSite = $request->ddlSiteSearch;
        $searchJob = $request->ddlJobSearch;
        $searchRole = $request->ddlRoleSearch;
        $searchEmpCode = $request->txtEmpCodeSearch;
        $searchEmpName = $request->txtEmpNameSearch;
        $employees = Employee::orderBy('MOC_M_EMPLOYEE.EMPLOYEE_ID', 'ASC')->where('FLAG_DELETE', '!=', 'Y')
            ->where('MOC_M_EMPLOYEE.FLAG_ACTIVE', 'Y');

        if ($searchSite) {
            $employees->where('MOC_M_EMPLOYEE.SITE_CODE', 'LIKE', "%$searchSite");
        }
        if ($searchJob) {
            $employees->where('MOC_M_EMPLOYEE.JOB_CODE', 'LIKE', "%$searchJob");
        }
        if ($searchRole) {
            $employees->leftJoin('MOC_T_EMPLOYEE_ROLE', 'MOC_M_EMPLOYEE.EMPLOYEE_CODE', '=', 'MOC_T_EMPLOYEE_ROLE.EMPLOYEE_CODE')
                     ->where('MOC_T_EMPLOYEE_ROLE.ROLE_CODE', 'LIKE', "%$searchRole%");
        }
        
        if ($searchEmpCode) {
            $employees->where('MOC_M_EMPLOYEE.EMPLOYEE_CODE', 'LIKE', "%$searchEmpCode");
        }
        if ($searchEmpName) {
            $employees->where(DB::raw("CONCAT(FIRST_NAME, ' ', LAST_NAME)"), 'LIKE', "%".$searchEmpName."%");
        }
        $employees = $employees->paginate(10);
        $employeeSearch = Employee::orderBy('EMPLOYEE_ID', 'ASC')->where('FLAG_ACTIVE', 'Y')->where('FLAG_DELETE', '!=', 'Y')->paginate(5);
        return view('employee.index', compact('empRole','employees', 'employeeSearch', 'menus', 'jobs', 'roles', 'sites', 'uniqueOrg'));
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
            $searchJob = $request->ddlJobSearch;
            $not_emp = $request->not_emp;
            $empSearch = Employee::orderBy('MOC_M_EMPLOYEE.EMPLOYEE_ID', 'ASC')
            ->when($not_emp, function (Builder $query, string $not_emp) {
                $query->where('EMPLOYEE_CODE', '!=',$not_emp);
            });
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
            Log::error('[EmployeeController/SearchEmp] Error occurred: ' . $th->getMessage());
        }
    }
    public function SearchRole(Request $request)
    {
        try {
            $txtRoleNameSearch = $request->txtRoleNameSearch;
            $empCode = $request->empCode;
            $roleSearch = Role::query();
            
            //Search Condition------------------------------
            if (!empty($txtRoleNameSearch)) {
                $roleSearch->where('ROLE_CODE', 'like', '%' . $txtRoleNameSearch . '%')
                ->orWhere('ROLE_NAME', 'like', '%' . $txtRoleNameSearch . '%');
            }
            //----------------------------------------------

            $roleSearch = $roleSearch->get();

            $data = [
                'roleSearch' => ['data'=>$roleSearch],
            ];

            return response()->json($data);

        } catch (\Throwable $th) {
            Log::error('[EmployeeController/SearchRole] Error occurred: ' . $th->getMessage());
        }
    }

    public function GetEmp($selectedEmployee)
    {
        
        $employee = Employee::where('EMPLOYEE_ID', $selectedEmployee)->first();
        $sites = Site::where('SITE_CODE', $employee->SITE_CODE)->orWhere('SITE_ADMIN',$employee->EMPLOYEE_CODE)->get();
        $data = [
            'employee' => $employee,
            'sites' => $sites,
        ];
        return response()->json($data);
    }

    public function GetRole($selectedRoles)
    {
        
        $roleIds = explode(',', $selectedRoles);
        $roles = Role::whereIn('ID', $roleIds)->get();
        $data = [
            'roles' => $roles,
        ];
        return response()->json($data);
    }

    
    public function Store(Request $request)
    {
        try{
        $check_code = Employee::where('EMPLOYEE_CODE', $request->txtEmpCode)->where('FLAG_DELETE', '!=', 'Y')->first();
        if($check_code){
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการบันทึก',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้ เลขประจำตัว '.$request->txtEmpCode.' มีผู้ใช้แล้ว',
                'icon' => 'error',
            ];
            session()->flash('alert', $alertData);
            return redirect()->back();
        }
        if($request->hdfFirstName == ''){
            if(count(explode(" ",$request->txtEmpName)) == 2){
                $request['hdfFirstName'] = explode(" ",$request->txtEmpName)[0];
                $request['hdfLastName'] = explode(" ",$request->txtEmpName)[1];
            }else{
                $request['hdfFirstName'] = $request->txtEmpName;
            }
        }

        // remove role 
        EmployeeRole::where('EMPLOYEE_CODE', $request->txtEmpCode)
        ->delete();

        $employees = Employee::create([
            'FIRST_NAME' => $request->hdfFirstName,
            'LAST_NAME' => $request->hdfLastName,
            'EMPLOYEE_CODE' => $request->txtEmpCode,
            'JOB_CODE' => $request->hdfJobCode,
            'JOB_NAME' => $request->txtJobName,
            'ORG_CODE_TH' => $request->txtOrgCode,
            'SITE_CODE' => $request->hdfSiteCode,
            'CREATE_DATE'=>  Carbon::now(),
            'CREATE_BY'=> session('emp_code'),
            'FLAG_ACTIVE'=> 'Y',
            'FLAG_DELETE'=> 'N'
        ]);

        $employeeRole = EmployeeRole::create([
            'EMPLOYEE_CODE' => $request->txtEmpCode,
            'ROLE_CODE' => $request->txtRoleCode,
            'CREATE_DATE'=>  Carbon::now(),
            'CREATE_BY'=> session('emp_code'),
            'FLAG_ACTIVE'=> 'Y',
        ]);

        if ($employees && $employeeRole) {
                $alertData = [
                    'title' => 'บันทึกสำเร็จ',
                    'message' => 'ทำการบันทึกข้อมูลสำเร็จแล้ว',
                    'icon' => 'success',
                ];
            }

        }catch (\Throwable $th) {
            Log::error('[EmployeeController/Store] Error occurred: ' . $th->getMessage());
            
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการบันทึก',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาตรวจสอบข้อผิดพลาด',
                'icon' => 'error',
            ];
        }
        
        session()->flash('alert', $alertData);
        return redirect()->route('employee');
    }
    public function Edit($id)
    {
        
        $employees = Employee::where('EMPLOYEE_ID', $id)->where('FLAG_DELETE', '!=', 'Y')->first();
        $jobs = Job::where('JOB_CODE', $employees->JOB_CODE)->first();
        $sites = Site::where('SITE_CODE', $employees->SITE_CODE)->orWhere('SITE_ADMIN',$employees->EMPLOYEE_CODE)->get();
        $empRole = EmployeeRole::where('EMPLOYEE_CODE', $employees->EMPLOYEE_CODE)->get();
        $data = [
            'employees' => $employees,
            'jobs' => $jobs,
            'sites' => $sites,
            'empRole' => $employees->roles,
        ];
        return response()->json($data);
    }
    public function GetEditEmp($selectedEmployee)
    {
        $employee = Employee::where('EMPLOYEE_ID', $selectedEmployee)->where('FLAG_DELETE', '!=', 'Y')->first();
        $site = Site::where('SITE_CODE', $employee->SITE_CODE)->first();
        $data = [
            'employee' => $employee,
            'site' => $site,
        ];
        return response()->json($data);
    }

    public function GetEditRole($selectedRoles)
    {
        $roleIds = explode(',', $selectedRoles);
        $roles = Role::whereIn('ID', $roleIds)->get();
        $data = [
            'roles' => $roles,
        ];
        return response()->json($data);
    }
    public function Update(Request $request)
    {
        try {
            $roleWords = explode(', ',$request->txtRoleCodeUpdate);
            $roles = Role::whereIn('ROLE_CODE', $roleWords)->get();
            if(count($roleWords) !== $roles->count()){
                foreach ($roleWords as $w) {
                    if(!in_array($w,$roles->pluck('ROLE_CODE')->all())){
                        $alertData = [
                            'title' => 'เกิดข้อผิดพลาดในการบันทึก',
                            'message' => 'ไม่พบ หน้าที่ "'.$w.'"',
                            'icon' => 'error',
                        ];
                        session()->flash('alert', $alertData);
                        return redirect()->route('employee');
                    }
                }
            }

            $employeesQuery = Employee::where('EMPLOYEE_ID', $request->hdfEmpIdUpdate)->where('FLAG_DELETE', '!=', 'Y')->first();
            // $employeesQuery->FIRST_NAME = $request->hdfFirstNameUpdate;
            // $employeesQuery->LAST_NAME = $request->hdfLastNameUpdate;
            // $employeesQuery->EMPLOYEE_CODE = $request->txtEmpCodeUpdate;
            // $employeesQuery->JOB_CODE = $request->hdfJobCodeUpdate;
            // $employeesQuery->JOB_NAME = $request->txtJobNameUpdate;
            // $employeesQuery->ORG_CODE_TH = $request->txtOrgCodeUpdate;
            // $employeesQuery->SITE_CODE = $request->hdfSiteCodeUpdate;
            $employeesQuery->UPDATE_DATE = Carbon::now();
            $employeesQuery->UPDATE_BY = session('emp_code');
            $rs_save = $employeesQuery->save();

            // remove role 
            EmployeeRole::where('EMPLOYEE_CODE', $employeesQuery->EMPLOYEE_CODE)
            ->whereNotIn('ROLE_CODE', $roleWords)
            ->delete();

            // insert role
            foreach ($roleWords as $w) {
                $find =  EmployeeRole::where('EMPLOYEE_CODE', $employeesQuery->EMPLOYEE_CODE)->where('ROLE_CODE', $w)->first();
                if(!$find){
                    EmployeeRole::create([
                        'EMPLOYEE_CODE'=> $employeesQuery->EMPLOYEE_CODE,
                        'ROLE_CODE' => $w,
                        'CREATE_DATE' => Carbon::now(),
                        'CREATE_BY' => session('emp_code')
                    ]);
                }
            }
            
            $alertData = [
                'title' => 'บันทึกสำเร็จ',
                'message' => 'ทำการแก้ไขข้อมูลสำเร็จแล้ว',
                'icon' => 'success',
            ];

        }catch (\Throwable $th) {
            Log::error('[EmployeeController/Update] Error occurred: ' . $th->getMessage());
            
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการบันทึก',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาตรวจสอบข้อผิดพลาด',
                'icon' => 'error',
            ];
        }
        
        session()->flash('alert', $alertData);
        return redirect()->route('employee');
    }
    public function Delete(Request $request)
    {
        try{
            $employeesQuery = Employee::where('EMPLOYEE_ID', $request->hdfDeleteEmpID)->where('FLAG_DELETE', '!=', 'Y')->first();
            $employeesQuery->UPDATE_DATE = Carbon::now();
            $employeesQuery->UPDATE_BY = session('emp_code');
            $employeesQuery->FLAG_ACTIVE = 'N';
            $employeesQuery->FLAG_DELETE = 'Y';
            $rs_save = $employeesQuery->save();

            // remove role 
            EmployeeRole::where('EMPLOYEE_CODE', $employeesQuery->EMPLOYEE_CODE)
            ->delete();
           
            if ($rs_save) {
                $alertData = [
                    'title' => 'สำเร็จ',
                    'message' => 'ทำการลบข้อมูลสำเร็จแล้ว',
                    'icon' => 'success',
                ];
            }

        }catch (\Throwable $th) {
            Log::error('[EmployeeController/Delete] Error occurred: ' . $th->getMessage());
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการบันทึก',
                'message' => 'ไม่สามารถลบข้อมูลได้ กรุณาตรวจสอบข้อผิดพลาด',
                'icon' => 'error',
            ];
        }

        session()->flash('alert', $alertData);
        return redirect()->route('employee');
    }
}
