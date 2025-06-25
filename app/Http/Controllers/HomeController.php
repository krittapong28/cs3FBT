<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Employee;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function Login(Request $request)
    {
        return view('auth.login');
    }


    public function OneLogin(Request $request)
    {    
        $subdomain = config('app.subdomain');
        $client_id = config('app.client_id');
        $redirect_uri = config('app.redirect_uri');

        if (!session('name') && !$request->has('code')){//หากไม่มีค่า

            //เรียก API Auth Code Flow pt.1
            $apiAuth = 'https://'.$subdomain.'.onelogin.com/oidc/2/auth?client_id='.$client_id.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=openid';
            return redirect()->to($apiAuth);
        }
        
        else if(session('name') != null){
            return redirect()->route('home');
        }
        
        else if($request->has('code') && !session('name')) {
            
            $responseAuth = $this->AuthPost($request->code);
            
            if ($responseAuth->successful()) {// ตรวจสอบว่า response มีสถานะเป็น 2xx (success) หรือไม่
                
                $id_token = $responseAuth->json()['id_token']; // ดึง id_token จาก response ที่ได้
                $access_token = $responseAuth->json()['access_token']; // ดึง access_token จาก response ที่ได้

                Session::put('id_token', $id_token);// id_token นำไปใช้ใช้ตอน logout
                Session::put('access_token', $access_token);

                $responseUserInfo = $this->GetUserInfo($access_token);
                //dd($responseUserInfo->json());
                if ($responseUserInfo->successful()) {
                    // นำข้อมูลที่ได้มาเก็บใน session
                    $sub = $responseUserInfo->json()['sub'];
                    $email = $responseUserInfo->json()['email'];
                    $preferred_username = $responseUserInfo->json()['preferred_username'];
                    $name = $responseUserInfo->json()['name'];
                    if (strlen($preferred_username) === 6) {
                        // Concatenate "00" before $empCode
                        $preferred_username = '00' . $preferred_username;
                    }
                    Session::put('sub', $sub);
                    Session::put('email', $email);
                    Session::put('preferred_username', $preferred_username);
                    Session::put('name', $name);
                    Session::put('emp_code', $preferred_username);
                    
                    $this->getDataEmpAuthenFromApi();

                    return redirect()->route('home');
                }
            }

        }
    }

    public function getDataEmpAuthenFromApi()
    {
        try {
            // API endpoint
            $apiUrl = config('app.url_person');
            $token = config('app.token_hr');
            $personCode = session('emp_code');//'590614';

            // Request headers
            $headers = [
                'Authorization' => $token,
                // Add any other headers as needed
            ];

            $queryParam = [
                'filter[PersonCode]' => sprintf("%08d", $personCode),
                'include' => 'positions.organization.manager,organizations,workLocations',
            ];

            // Make a GET request with headers and body
            $response = Http::withHeaders($headers)->get($apiUrl, $queryParam);

            // Process the API response (e.g., decode JSON)
            $responseData = $response->json();

            // Check if 'data' key exists
            if (!isset($responseData['data'])) {
                throw new \Exception("Invalid response structure: 'data' key not found.");
            }

            // Enable query logging
            DB::enableQueryLog();

            // Process the data
            foreach ($responseData['data'] as $data) {
                $currentDateTime = now(); // Use Laravel's helper function to get the current date and time
                $newEmployeeData = [
                    'EMPLOYEE_CODE' => $data['person_code'] ?? null,
                    'TITLE_NAME' => $data['person_thai_title_name'] ?? null,
                    'PREFIX_NAME' => $data['person_thai_prefix_name'] ?? null,
                    'FIRST_NAME' => $data['person_thai_thai_firstname'] ?? null,
                    'LAST_NAME' => $data['person_thai_thai_lastname'] ?? null,
                    'EMAIL' => $data['person_mail_address'] ?? null,
                    'ORG_CODE' => $data['main_org_code'] ?? null,
                    'ORG_CODE_TH' => $data['main_org_thai_name_path'] ?? null,
                    'SITE_CODE' => $data['main_org_cost_center_code'] ?? null,
                    'ORG_GROUP_LEVEL' => intval($data['main_org_group_level'] ?? 0),
                    'ORG_DEPUTY_ID' => $data['main_org_deputy_id'] ?? null,
                    'ORG_DEPUTY_CODE' => $data['main_org_deputy_thai_short_name'] ?? null,
                    'ORG_ASSIST_ID' => $data['main_org_assist_id'] ?? null,
                    'ORG_ASSIST_CODE' => $data['main_org_assist_thai_short_name'] ?? null,
                    'ORG_DIVISION_ID' => $data['main_org_division_id'] ?? null,
                    'ORG_DIVISION_CODE' => $data['main_org_division_thai_short_name'] ?? null,
                    'ORG_DEPARTMENT_ID' => $data['main_org_department_id'] ?? null,
                    'ORG_DEPARTMENT_CODE' => $data['main_org_department_thai_short_name'] ?? null,
                    'ORG_SECTION_ID' => $data['main_org_section_id'] ?? null,
                    'ORG_SECTION_CODE' => $data['main_org_section_thai_short_name'] ?? null,
                    'JOB_CODE' => $data['positions'][0]['PST_TShortName'] ?? null,
                    'JOB_NAME' => $data['positions'][0]['PST_TLongName'] ?? null,
                    'TEL' => $data['work_location'][0]['location']['phone_number'] ?? null,
                    'CREATE_DATE' => $currentDateTime,
                    'CREATE_BY' => 'system',
                    'UPDATE_DATE' => $currentDateTime,
                    'UPDATE_BY' => 'system',
                    'FLAG_DELETE' => 'N',
                    'FLAG_ACTIVE' => 'Y',
                    // Add other fields as needed
                ];

                // Log the data being passed
                \Log::info('Data to be updated or created', $newEmployeeData);

                // Try to update or create the employee
                try {
                    $employee = Employee::updateOrCreate(
                        ['EMPLOYEE_CODE' => $data['person_code']],
                        $newEmployeeData
                    );

                    // Log the employee instance
                    \Log::info('Employee updated or created', $employee->toArray());
                } catch (\Exception $e) {
                    \Log::error('Error updating or creating employee: ' . $e->getMessage());
                }
            }

            // Get the query log
            $queries = DB::getQueryLog();
            \Log::info('Query Log', $queries);

            // Return success response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection error, invalid response)
            \Log::error('Error occurred: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }



    
    function AuthPost($code) // เรียก API Auth Code Flow pt.2
    {
        $subdomain = config('app.subdomain');
        $client_id = config('app.client_id');
        $client_secret = config('app.client_secret');
        $redirect_uri = config('app.redirect_uri');

        $response = Http::asForm()->post('https://'.$subdomain.'.onelogin.com/oidc/2/token', [
            //Set body---------
            'code' => $code,
            'redirect_uri' => $redirect_uri,
            'grant_type' => 'authorization_code',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ]);
        return $response; 
    }

    function GetUserInfo($access_token)
    {
        $subdomain = config('app.subdomain');
        $response = Http::withToken($access_token)->get('https://'.$subdomain.'.onelogin.com/oidc/2/me');
        return $response;
    }

    function logout()
    {
        //หากเป็น user EGAT OneLogin ----------------------------------

        if(session('id_token') != null && session('preferred_username') != null){
            $id_token = session('id_token');
            Session::flush(); //เคลียร์ sesion ทั้งหมดของเว็บ
            session()->forget('_token');

            $subdomain = config('app.subdomain');
            $redirect_uri = config('app.logout_redirect_uri');
            $response_logout = 'https://'.$subdomain.'.onelogin.com/oidc/2/logout?post_logout_redirect_uri='.$redirect_uri.'&id_token_hint='.$id_token;
            return redirect()->to($response_logout);
        }
        //------------------------------------------------------------

        Session::flush();
        session()->forget('_token');

        return redirect()->route('login');
    }

     
    public function index()
    {

        if(session('emp_code')){
        
            $empCode = session('emp_code');
            $menus = Menu::getDistinctMenus($empCode);
        }else{
            return redirect()->route('login');
        }

        return view('home', compact('menus'));
    }

    public function welcome()
    {
        return redirect()->route('login');
    }

    public function OrgApiIndex(Request $request)
    {
        $menus = Menu::getDistinctMenus(session('emp_code'));
        return view('update.org', compact('menus'));
    }

    public function EmpApiIndex(Request $request)
    {
        $menus = Menu::getDistinctMenus(session('emp_code'));
        return view('update.employee', compact('menus'));
    }
}
