<?php
namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use DateTime;
use DateTimeZone;

class CategoryController extends Controller
{
    //===============================================================================================
    //  IndexPage
    //===============================================================================================
    public function Index(Request $request)
    {      
        $menus = Menu::getDistinctMenus(session('emp_code'));
        $categories = Category::where('FLAG_DELETE', '!=', 'Y');

        // Search-----------------------------------------------------------------------------
        if ($request->has('txtCategoryNameSearch') && !empty($request->txtCategoryNameSearch)) {
            $categories->where('CATEGORY_NAME', 'like', "%{$request->txtCategoryNameSearch}%");
        }
        if ($request->has('txtCategoryDescScearch') && !empty($request->txtCategoryDescScearch)) {
            $categories->where('CATEGORY_DESC', 'like', "%{$request->txtCategoryDescScearch}%");
        }
        if ($request->has('txtCreateMonthSearch') && !empty($request->txtCreateMonthSearch)) {
            $dateTime = $this->createDateFromThaiFormat('F Y', $request->txtCreateMonthSearch, config('app.timezone'));
            $month = $dateTime->format('m');
            $year = $dateTime->format('Y') - 543;
            $categories->whereMonth('CREATE_DATE', $month)->whereYear('CREATE_DATE', $year);
        }
        if ($request->has('txtCreateDateSearch') && !empty($request->txtCreateDateSearch)) {
            $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->txtCreateDateSearch)->subYears(543)->format('Y-m-d'); //แปลง 30/11/2566 -> 2023-11-30
            $categories->whereDate('CREATE_DATE', $formattedDate);
        }
        //-----------------------------------------------------------------------------------
        $categories = $categories->paginate(config('app.paginate')); // 5 items per page

        return view('categories.index', compact('categories', 'menus'));
    }

    //===============================================================================================
    //  Create
    //===============================================================================================
    public function Store(Request $request)
    {
        try{

            //Save file to stored-------------------------------------------------------------------------
            $emp_code = session('emp_code');
            $fileName = "";
            if ($request->hasFile('fileName')) {
                $fileName = $request->fileName->getClientOriginalName();
                $image = $request->file('fileName');
                $iname = date('Ym') . '-' . rand() . '.' . $image->getClientOriginalExtension();
                $store = $image->storeAs('public/Category', $iname);
                if ($store){
                    $request->FILE_VALUE = $iname;
                }
            }
            //--------------------------------------------------------------------------------------------

            // Get Max Category Code-----------------------------------------------------------------------
            $maxCode = Category::max('CATEGORY_CODE');
            $newCode = $maxCode ? str_pad((intval(substr($maxCode, 1)) + 1), 3, '0', STR_PAD_LEFT) : '001';
            $newCategoryCode = 'C' . $newCode;
            //---------------------------------------------------------------------------------------------

            if($request->chkFlagActive){
                $chkFlagActive = "Y";
            }else{
                $chkFlagActive = "N";
            }

            $category = Category::create([
                'CATEGORY_CODE' => $newCategoryCode,
                'CATEGORY_NAME' => $request->txtCategoryName,
                'CATEGORY_DESC' => $request->txtCategoryDesc,
                'FILE_NAME' => $fileName,
                'FILE_VALUE' => $request->FILE_VALUE,
                'CREATE_DATE' => Carbon::now(),
                'CREATE_BY' => $emp_code,
                'FLAG_ACTIVE' => $chkFlagActive,
                'FLAG_DELETE' => "N",
            ]);

            if ($category) { // การบันทึกสำเร็จ
                $alertData = [
                    'title' => 'บันทึกสำเร็จ',
                    'message' => 'ทำการบันทึกข้อมูลสำเร็จแล้ว',
                    'icon' => 'success',
                ];
            }

        }catch (\Throwable $th) { // จัดการข้อผิดพลาดที่ไม่คาดคิด
            Log::error('[CategoryController/Store] Error occurred: ' . $th->getMessage());
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการบันทึก',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาตรวจสอบข้อผิดพลาด',
                'icon' => 'error',
            ];
        }
        
        session()->flash('alert', $alertData);
        return redirect()->route('categories.index');
    }

    //===============================================================================================
    //  GetEdit
    //===============================================================================================
    public function Edit($id)
    {
        $category = Category::where('ID', $id)->first();
        return response()->json($category);
    }

    //===============================================================================================
    //  Update
    //===============================================================================================
    public function Update(Request $request)
    {
        try{

            if($request->chkFlagActiveUpdate){
                $chkFlagActiveUpdate = "Y";
            }else{
                $chkFlagActiveUpdate = "N";
            }

            $emp_code = session('emp_code');
            $category = Category::where('ID', $request->hdfIdUpdate)->first();
            $category->CATEGORY_NAME = $request->txtCategoryNameUpdate;
            $category->CATEGORY_DESC = $request->has('txtCategoryDescUpdate') ? $request->txtCategoryDescUpdate : null;
            $category->UPDATE_DATE = Carbon::now();
            $category->FLAG_ACTIVE = $chkFlagActiveUpdate;
        
            //Save file to stored------
            $fileName = "";
            if ($request->hasFile('fileNameUpdate')) {
                $fileName = $request->fileNameUpdate->getClientOriginalName();
                $image = $request->file('fileNameUpdate');
                $iname = date('Ym') . '-' . rand() . '.' . $image->getClientOriginalExtension();
                $store = $image->storeAs('public/Category', $iname);
                if ($store){
                    if (!empty($category->FILE_VALUE)) {
                        Storage::delete('public/Category/' . $category->FILE_VALUE);//ลบไฟล์เดิม
                    }
                    $category->FILE_NAME = $fileName;//Update FILE_NAME(ไฟล์ใหม่)
                    $category->FILE_VALUE = $iname;//Update FILE_VALUE(ไฟล์ใหม่)
                }
            }

            $category->UPDATE_BY = $emp_code;
            //--------------------------
            $rs_save = $category->save();
            
            if ($rs_save) { // การบันทึกสำเร็จ
                $alertData = [
                    'title' => 'สำเร็จ',
                    'message' => 'ทำการบันทึกข้อมูลสำเร็จแล้ว',
                    'icon' => 'success',
                ];
            }

        }catch (\Throwable $th) { // จัดการข้อผิดพลาดที่ไม่คาดคิด
            Log::error('[CategoryController/Update] Error occurred: ' . $th->getMessage());
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการบันทึก',
                'message' => 'ไม่สามารถบันทึกข้อมูลได้ กรุณาตรวจสอบข้อผิดพลาด',
                'icon' => 'error',
            ];
        }

        session()->flash('alert', $alertData);
        return redirect()->route('categories.index');
    }

    //===============================================================================================
    //  Delete
    //===============================================================================================
    public function Delete($id)
    {
        try{
            $emp_code = session('emp_code');
            $category = Category::where('ID', $id)->first();
            $category->UPDATE_DATE = Carbon::now();
            $category->UPDATE_BY = $emp_code;
            $category->FLAG_DELETE = "Y";

            $rs_save = $category->save();
            
            if ($rs_save) { // การลบสำเร็จ
                $alertData = [
                    'title' => 'ลบสำเร็จ',
                    'message' => 'ทำการลบข้อมูลสำเร็จแล้ว',
                    'icon' => 'success',
                ];
            }

        }catch (\Throwable $th) { // จัดการข้อผิดพลาดที่ไม่คาดคิด
            Log::error('[CategoryController/Delate] Error occurred: ' . $th->getMessage());
            $alertData = [
                'title' => 'เกิดข้อผิดพลาดในการลบ',
                'message' => 'ไม่สามารถลบข้อมูลได้ กรุณาตรวจสอบข้อผิดพลาด',
                'icon' => 'error',
            ];
        }

        session()->flash('alert', $alertData);
        return redirect()->route('categories.index');
    }

    function createDateFromThaiFormat($format, $dateString, $timezone = null)
    {
        $months = [
            'มกราคม' => 'January',
            'กุมภาพันธ์' => 'February',
            'มีนาคม' => 'March',
            'เมษายน' => 'April',
            'พฤษภาคม' => 'May',
            'มิถุนายน' => 'June',
            'กรกฎาคม' => 'July',
            'สิงหาคม' => 'August',
            'กันยายน' => 'September',
            'ตุลาคม' => 'October',
            'พฤศจิกายน' => 'November',
            'ธันวาคม' => 'December',
        ];

        $dateString = str_replace(array_keys($months), array_values($months), $dateString);
        $dateTime = DateTime::createFromFormat($format, $dateString, new DateTimeZone($timezone));

        return DateTime::createFromFormat($format, $dateString, new DateTimeZone($timezone));
    }

}
