<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\ActorHistory;
use App\Models\Delegate;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Menu;
use App\Models\RequestResult;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DelegateSettingController extends Controller
{
    function convertThaiDateToGregorian($thaiDate)
    {
        $thaiMonths = [
            'มกราคม' => '01',
            'กุมภาพันธ์' => '02',
            'มีนาคม' => '03',
            'เมษายน' => '04',
            'พฤษภาคม' => '05',
            'มิถุนายน' => '06',
            'กรกฎาคม' => '07',
            'สิงหาคม' => '08',
            'กันยายน' => '09',
            'ตุลาคม' => '10',
            'พฤศจิกายน' => '11',
            'ธันวาคม' => '12'
        ];

        foreach ($thaiMonths as $thaiMonth => $monthNumber) {
            if (strpos($thaiDate, $thaiMonth) !== false) {
                $year = (int)substr($thaiDate, strpos($thaiDate, $thaiMonth) + strlen($thaiMonth) + 1) - 543;
                return "$year-$monthNumber-01";
            }
        }
        return null;
    }

    public function index(Request $request)
    {
        $menus = Menu::getDistinctMenus(session('emp_code'));
        // in modal
        $siteSearch = Site::where('FLAG_ACTIVE', 'Y')->where('FLAG_DELETE', '!=', 'Y')->get();
        $orgCodes = Employee::select('ORG_CODE_TH')->where('FLAG_ACTIVE', 'Y')->groupBy('ORG_CODE_TH')->pluck('ORG_CODE_TH');

        
        $txtEmpName = $request->txtEmpName;
        $txtRequestNo = $request->txtRequestNo;
        $txtRequestName = $request->txtRequestName;
        $monthInputStart = $this->convertThaiDateToGregorian($request->monthInputStart);
        $monthInputEnd = $this->convertThaiDateToGregorian($request->monthInputEnd);
        if($monthInputStart){
            $monthInputStart = Carbon::parse($monthInputStart)->startOfMonth();
        }
        if($monthInputEnd){
            $monthInputEnd = Carbon::parse($monthInputEnd)->endOfMonth();
        }
        $searchTypeRIK = $request->ckTypeRIK;
        $searchTypeNONRIK = $request->ckTypeNONRIK;
        $searchNonRikOption = $searchTypeNONRIK == "NONRIK" ? $request->nonrik_option : "";

        $REQUEST_STATUS = [];
        if($searchTypeRIK){
            $REQUEST_STATUS[] = $searchTypeRIK;
        }
        if($searchTypeNONRIK){
            $REQUEST_STATUS[] = $searchTypeNONRIK;
        }

        if ($searchTypeNONRIK && $searchNonRikOption && $searchNonRikOption != "all") {
            $where[] = "DURATION_CHANGE = '" . $searchNonRikOption . "'";
        }


        $worklist = Actor::select(
            'MOC_T_REQUEST_ACTOR.*',
            'MOC_T_REQUEST.CHANGE_NAME',
            'MOC_T_REQUEST.DOCUMENT_CODE',
            'MOC_M_EMPLOYEE.FIRST_NAME',
            'MOC_M_EMPLOYEE.LAST_NAME',
            'MOC_T_REQUEST_STATUS.REQUEST_STATUS',
        )
            ->join('MOC_T_REQUEST', 'MOC_T_REQUEST.REQUEST_CODE', '=', 'MOC_T_REQUEST_ACTOR.REQUEST_CODE')
            ->leftJoin('MOC_M_EMPLOYEE', 'MOC_M_EMPLOYEE.EMPLOYEE_CODE', '=', 'MOC_T_REQUEST_ACTOR.EMP_CODE')
            ->join('MOC_T_REQUEST_STATUS', 'MOC_T_REQUEST_STATUS.REQUEST_CODE', '=', 'MOC_T_REQUEST_ACTOR.REQUEST_CODE')
            ->where('MOC_T_REQUEST.REQUEST_CODE', '!=', 'FIN')
            ->where('MOC_T_REQUEST_ACTOR.EMP_CODE',session('emp_code'))
            ->orderBy('MOC_T_REQUEST_ACTOR.ID', 'DESC')
            ->when($txtEmpName, function (Builder $query, string $txtEmpName) {
                $query->where(DB::raw("CONCAT(MOC_M_EMPLOYEE.FIRST_NAME,' ',MOC_M_EMPLOYEE.LAST_NAME)"), 'LIKE', "%".$txtEmpName."%");
            })
            ->when($txtRequestNo, function (Builder $query, string $txtRequestNo) {
                $query->where('MOC_T_REQUEST.REQUEST_CODE', 'LIKE',"%$txtRequestNo%");
            })
            ->when($txtRequestName, function (Builder $query, string $txtRequestName) {
                $query->where('MOC_T_REQUEST.CHANGE_NAME', 'LIKE',"%$txtRequestName%");
            })
            ->when($monthInputStart, function (Builder $query,string $monthInputStart){
                $query->whereDate('MOC_T_REQUEST_ACTOR.CREATE_DATE','>=', $monthInputStart);
            })
            ->when($monthInputEnd, function (Builder $query,string $monthInputEnd){
                $query->whereDate('MOC_T_REQUEST_ACTOR.CREATE_DATE','<=', $monthInputEnd);
            })
            ->when(count($REQUEST_STATUS), function (Builder $query) use($REQUEST_STATUS){
                $query->whereIn('MOC_T_REQUEST.TYPE_OF_CHANGE', $REQUEST_STATUS);
            })
            ->when($searchTypeNONRIK && $searchNonRikOption && $searchNonRikOption != "all", function (Builder $query,)use($searchNonRikOption) {
                $query->where('MOC_T_REQUEST.DURATION_CHANGE',$searchNonRikOption);
            })
            ->get();
        
        $new_worklist = [];
        foreach ($worklist as $key => $item) {
            $result = RequestResult::where('REQUEST_CODE',$item->REQUEST_CODE)
            ->where('RESULT','APP')
            ->where('CURRENT_ROLE',$item->ROLE_CODE)
            ->where('NEXT_BY',$item->EMP_CODE)
            ->whereNull('FINISH')
            ->first();
            if(!$result){
                continue;
            }
            $new_worklist[] = $item;
        }
            

        $worklist = $this->paginate($new_worklist, 5);
        $worklist->setPath(route('delegate-setting'));

        

        $txtEmpName = $request->his_txtEmpName;
        $txtRequestNo = $request->his_txtRequestNo;
        $txtRequestName = $request->his_txtRequestName;
        $txtUpdateBy = $request->his_txtUpdateBy;
        $updateDate = $request->his_updateDate;
        if($updateDate){
            $updateDate = Carbon::createFromFormat('d/m/Y', $updateDate)->subYears(543)->format('Y-m-d');
        }
        
        $monthInputStart = $this->convertThaiDateToGregorian($request->his_monthInputStart);
        $monthInputEnd = $this->convertThaiDateToGregorian($request->his_monthInputEnd);
        if($monthInputStart){
            $monthInputStart = Carbon::parse($monthInputStart)->startOfMonth();
        }
        if($monthInputEnd){
            $monthInputEnd = Carbon::parse($monthInputEnd)->endOfMonth();
        }
        $searchTypeRIK = $request->his_ckTypeRIK;
        $searchTypeNONRIK = $request->his_ckTypeNONRIK;
        $searchNonRikOption = $searchTypeNONRIK == "NONRIK" ? $request->his_nonrik_option : "";

        $REQUEST_STATUS = [];
        if($searchTypeRIK){
            $REQUEST_STATUS[] = $searchTypeRIK;
        }
        if($searchTypeNONRIK){
            $REQUEST_STATUS[] = $searchTypeNONRIK;
        }

        if ($searchTypeNONRIK && $searchNonRikOption && $searchNonRikOption != "all") {
            $where[] = "DURATION_CHANGE = '" . $searchNonRikOption . "'";
        }

        $delegateHistory = ActorHistory::select(
            'MOC_T_REQUEST_ACTOR_HISTORY.*',
            'MOC_T_REQUEST.CHANGE_NAME',
            'MOC_T_REQUEST.DOCUMENT_CODE'
        )
            ->join('MOC_T_REQUEST', 'MOC_T_REQUEST.REQUEST_CODE', '=', 'MOC_T_REQUEST_ACTOR_HISTORY.REQUEST_CODE')
            ->leftJoin('MOC_M_EMPLOYEE as emp', 'emp.EMPLOYEE_CODE', '=', 'MOC_T_REQUEST_ACTOR_HISTORY.EMP_CODE_NEW')
            ->leftJoin('MOC_M_EMPLOYEE as updateBy', 'updateBy.EMPLOYEE_CODE', '=', 'MOC_T_REQUEST_ACTOR_HISTORY.CREATE_BY')
            ->where('MOC_T_REQUEST_ACTOR_HISTORY.CREATE_BY',session('emp_code'))
            ->when($txtEmpName, function (Builder $query, string $txtEmpName) {
                $query->where(DB::raw("CONCAT(emp.FIRST_NAME,' ',emp.LAST_NAME)"), 'LIKE', "%".$txtEmpName."%");
            })
            ->when($txtRequestNo, function (Builder $query, string $txtRequestNo) {
                $query->where('MOC_T_REQUEST.REQUEST_CODE', 'LIKE',"%$txtRequestNo%");
            })
            ->when($txtRequestName, function (Builder $query, string $txtRequestName) {
                $query->where('MOC_T_REQUEST.CHANGE_NAME', 'LIKE',"%$txtRequestName%");
            })
            ->when($monthInputStart, function (Builder $query,string $monthInputStart){
                $query->whereDate('MOC_T_REQUEST_ACTOR_HISTORY.CREATE_DATE','>=', $monthInputStart);
            })
            ->when($monthInputEnd, function (Builder $query,string $monthInputEnd){
                $query->whereDate('MOC_T_REQUEST_ACTOR_HISTORY.CREATE_DATE','<=', $monthInputEnd);
            })
            ->when(count($REQUEST_STATUS), function (Builder $query) use($REQUEST_STATUS){
                $query->whereIn('MOC_T_REQUEST.TYPE_OF_CHANGE', $REQUEST_STATUS);
            })
            ->when($searchTypeNONRIK && $searchNonRikOption && $searchNonRikOption != "all", function (Builder $query,)use($searchNonRikOption) {
                $query->where('MOC_T_REQUEST.DURATION_CHANGE',$searchNonRikOption);
            })
            ->when($txtUpdateBy, function (Builder $query, string $txtUpdateBy) {
                $query->where(DB::raw("CONCAT(updateBy.FIRST_NAME,' ',updateBy.LAST_NAME)"), 'LIKE', "%".$txtUpdateBy."%");
            })
            ->when($updateDate, function (Builder $query, string $updateDate) {
                $query->whereDate('MOC_T_REQUEST_ACTOR_HISTORY.CREATE_DATE',$updateDate);
            })
            ->orderBy('MOC_T_REQUEST_ACTOR_HISTORY.ID', 'DESC')
            ->paginate(5, ['*'], 'history_page');

        return view('delegate_setting.index', compact(
            'menus',
            'worklist',
            'delegateHistory',
            'siteSearch',
            'orgCodes',
        ));
    }

    public function SearchEmp(Request $request)
    {
        try {
            $ddlOrgCodeSearch = $request->ddlOrgCodeSearch;
            $ddlSiteSearch = $request->ddlSiteSearch;
            $txtJobSearch = $request->txtJobSearch;
            $txtEmpCodeSearch = $request->txtEmpCodeSearch;
            $txtEmpNameSearch = $request->txtEmpNameSearch;
            $searchJob = $request->ddlJobSearch;
            $empSearch = Employee::where('FLAG_ACTIVE', 'Y')->orderBy('MOC_M_EMPLOYEE.EMPLOYEE_ID', 'ASC');
            $sites = Site::select('SITE_CODE', 'SITE_NAME')->get();
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
                $empSearch->where('EMPLOYEE_CODE', 'LIKE', "%" . $txtEmpCodeSearch . "%");
            }
            if (!empty($txtEmpNameSearch)) {
                $empSearch->where(DB::raw("CONCAT(FIRST_NAME, ' ', LAST_NAME)"), 'LIKE', "%" . $txtEmpNameSearch . "%");
            }
            //----------------------------------------------

            $empSearch = $empSearch->paginate(5);

            $data = [
                'sites' => $sites,
                'jobs' => $jobs,
                'empSearch' => $empSearch,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            Log::error('[SiteController/SearchEmp] Error occurred: ' . $th->getMessage());
        }
    }


    public function update(Request $request, string $id = null)
    {

        try {

            $to_emp_code = $request->hdfEmpCodeUpdate;
            $actor = Actor::findOrFail($request->ACTOR_ID);

            RequestResult::where('REQUEST_CODE', $actor->REQUEST_CODE)
                ->where('NEXT_BY', $actor->EMP_CODE)
                ->where('FINISH', null)->update([
                    'NEXT_BY' => $to_emp_code
                ]);
            ActorHistory::create([
                'ACTOR_ID' => $actor->ID,
                'REQUEST_CODE' => $actor->REQUEST_CODE,
                'DUTY_CODE' => $actor->DUTY_CODE,
                'ITEM_NO' => $actor->ITEM_NO,
                'ITEM_DESC' => $actor->ITEM_DESC,
                'ROLE_CODE' => $actor->ROLE_CODE,
                'EMP_CODE' => $actor->EMP_CODE,
                'EMP_CODE_NEW' => $to_emp_code,
                'DELEGATE_CODE' => $actor->DELEGATE_CODE,
                'LEAD_CHECK' => $actor->LEAD_CHECK,
                'CREATE_DATE' => Carbon::now(),
                'CREATE_BY' => session('emp_code'),
            ]);

            $actor->update([
                'EMP_CODE' => $to_emp_code,
                'UPDATE_DATE' => Carbon::now(),
                'UPDATE_BY' => session('emp_code'),
            ]);

            $alertData = [
                'title' => 'บันทึกสำเร็จ',
                'message' => 'ทำการบันทึกข้อมูลสำเร็จแล้ว',
                'icon' => 'success',
            ];
        } catch (\Throwable $th) {
            Log::error('[DelegateSettingController/Update] Error occurred: ' . $th->getMessage());
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
