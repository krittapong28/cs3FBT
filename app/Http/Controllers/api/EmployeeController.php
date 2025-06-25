<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeeSingleResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class EmployeeController extends Controller
{
    public function __construct(Request $request)
    {
        
    }
    public function index(Request $request)
    {
        $employees = Employee::all();

        if ($request->search) {
            $employees = Employee::where('first_name', "like", "%{$request->search}%")
            ->orWhere('last_name', "like", "%{$request->search}%")
            ->get();
        } elseif ($request->department_id) {
            $employees = Employee::where('department_id', $request->department_id)->get();
        }

        return EmployeeResource::collection($employees);
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

    public function getDataFromApi()
    {   
        $responseAuth = $this->AuthPost(session('code'));
        if ($responseAuth->successful()) {// ตรวจสอบว่า response มีสถานะเป็น 2xx (success) หรือไม่
                
            $id_token = $responseAuth->json()['id_token']; // ดึง id_token จาก response ที่ได้
            $access_token = $responseAuth->json()['access_token']; // ดึง access_token จาก response ที่ได้

            Session::put('id_token', $id_token);// id_token นำไปใช้ใช้ตอน logout
            Session::put('access_token', $access_token);
            // API endpoint
            $apiUrl = 'https://hrapi.egat.co.th/api/v1/persons';

            // Request headers
            $headers = [
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
                // Add any other headers as needed
            ];

            // Request body (if needed)
            $body = [
                'filter[IsActive]' => true,
                // Add any other parameters as needed
            ];
            print_r($access_token);
            exit();

            try {
                // Make a GET request with headers and body
                $response = Http::withHeaders($headers)
                                ->withBody(json_encode($body), 'application/json')
                                ->get($apiUrl);

                // Process the API response (e.g., decode JSON)
                $responseData = $response->json();
                print_r($responseData);
                //exit();
                // Do something with the response data

                return response()->json(['success' => true, 'data' => $responseData]);
                print_r("Successful");
                exit();
            } catch (\Exception $e) {
                // Handle exceptions (e.g., connection error, invalid response)

                return response()->json(['success' => false, 'error' => $e->getMessage()]);
                print_r("Not Successful");
                exit();
            }
        }
    }


    public function store(EmployeeStoreRequest $request)
    {
        $employee = Employee::create($request->validated());

        return response()->json($employee);
    }


    public function show(Employee $employee)
    {
        return new EmployeeSingleResource($employee);
    }


    public function edit($id)
    {
        //
    }


    public function update(EmployeeStoreRequest $request, Employee $employee)
    {
        $employee->update($request->validated());
    }


    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json('Employee Deleted Successfully');
    }

}
