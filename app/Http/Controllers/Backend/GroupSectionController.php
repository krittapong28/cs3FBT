<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GroupSection;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Site;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class GroupSectionController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::getDistinctMenus(session('emp_code'));
        $group_sections = GroupSection::where('FLAG_ACTIVE', 'Y')->where('FLAG_DELETE', '!=', 'Y');

        // Search-----------------------------------------------------------------------------
        if ($request->has('searchGROUP_NAME') && !empty($request->searchGROUP_NAME)) {
            $group_sections->where('GROUP_NAME', 'like', "%{$request->searchGROUP_NAME}%");
        }
        if ($request->has('searchGROUP_DESC') && !empty($request->searchGROUP_DESC)) {
            $group_sections->where('GROUP_DESC', 'like', "%{$request->searchGROUP_DESC}%");
        }
        //-----------------------------------------------------------------------------------
        $group_sections = $group_sections
        ->orderBy('GROUP_NAME','ASC')
        ->paginate(5); // 5 items per page

        return view('group_sections.index', compact('group_sections', 'menus'));
    }

    //===============================================================================================
    //  Create
    //===============================================================================================
    public function store(Request $request)
    {
        // Get Max Group Section Code-----------------------------------------------------------------------
        // SUBSTRING('SQL Tutorial', 2,len('SQL Tutorial'))
        $maxCODE = GroupSection::select(DB::raw('MAX(SUBSTRING( GROUP_CODE, 2, LEN(GROUP_CODE))) as GROUP_CODE'))->pluck('GROUP_CODE');
        // return $maxCODE = GroupSection::max('MAX(SUBSTRING( GROUP_CODE, 2, LEN(GROUP_CODE))) as SEQ_ID');
        $newCode = $maxCODE ? str_pad((intval($maxCODE[0]) + 1), 3, '0', STR_PAD_LEFT) : '001';
        $newGroupCode = 'G' . $newCode;
        //---------------------------------------------------------------------------------------------
        $emp_code = session('emp_code');

        $group_section = GroupSection::create([
            'GROUP_CODE' => $newGroupCode,
            'GROUP_NAME' => $request->GROUP_NAME,
            'GROUP_DESC' => $request->GROUP_DESC,
            'CREATE_DATE' => Carbon::now(),
            'CREATE_BY' => $emp_code,
            'FLAG_ACTIVE' => $request->FLAG_ACTIVE != "N" ? "Y" : 'N',
            'FLAG_DELETE' => "N",
        ]);

        if ($group_section) { // การบันทึกสำเร็จ
            $alertData = [
                'title' => 'บันทึกสำเร็จ',
                'message' => 'ทำการบันทึกข้อมูลสำเร็จแล้ว',
                'icon' => 'success',
            ];
        } else { // การบันทึกไม่สำเร็จ
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการบันทึก',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้',
                'icon' => 'error',
            ];
        }
        session()->flash('alert', $alertData);
        return redirect()->route('group_sections.index');
    }


    public function edit($id)
    {
        $group_section = GroupSection::where('ID', $id)->first();
        return response()->json($group_section);
    }

    //===============================================================================================
    //  Update
    //===============================================================================================
    public function update(Request $request)
    {
        $emp_code = session('emp_code');
        $group_section = GroupSection::find($request->ID);        
        $group_section->GROUP_NAME = $request->GROUP_NAME;
        $group_section->GROUP_DESC = $request->has('GROUP_DESC') ? $request->GROUP_DESC : null;
        $group_section->UPDATE_DATE = Carbon::now();
        $group_section->FLAG_ACTIVE = $request->FLAG_ACTIVE != "N" ? "Y" : 'N';
        $group_section->UPDATE_BY = $emp_code;
        $rs_save = $group_section->save();
        
        if ($rs_save) { // การบันทึกสำเร็จ
            $alertData = [
                'title' => 'สำเร็จ',
                'message' => 'ทำการบันทึกข้อมูลสำเร็จแล้ว',
                'icon' => 'success',
            ];
        } else { // การบันทึกไม่สำเร็จ
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการบันทึก',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้',
                'icon' => 'error',
            ];
        }
        session()->flash('alert', $alertData);
        return redirect()->route('group_sections.index');
    }


    public function delete($id)
    {
        $group_section = GroupSection::where('ID', $id)->first();
        $group_section->UPDATE_DATE = Carbon::now();
        $group_section->FLAG_DELETE = "Y";

        $rs_save = $group_section->save();
        
        if ($rs_save) { // การลบสำเร็จ
            $alertData = [
                'title' => 'ลบสำเร็จ',
                'message' => 'ทำการลบข้อมูลสำเร็จแล้ว',
                'icon' => 'success',
            ];
        } else { // ลบไม่สำเร็จ
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการลบ',
                'message' => 'ไม่สามารถลบมูลได้',
                'icon' => 'error',
            ];
        }
        session()->flash('alert', $alertData);
        return redirect()->route('group_sections.index');
    }
}
