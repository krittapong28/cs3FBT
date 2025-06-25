<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Department;
use App\Models\State;
use App\Models\Employee;
use App\Models\EmployeeRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmployeeDataController extends Controller
{

    private $apiUrl = 'https://hrapi.egat.co.th/api/v1/persons';
    private $paginate = '100';
    private function token()
    {
        return config('app.token_hr');
    }

    public function countries()
    {
        $countries = Country::all();

        return response()->json($countries);
    }

    public function states(Country $country)
    {
        return response()->json($country->states);
    }

    public function cities(State $state)
    {
        return response()->json($state->cities);
    }

    public function departments()
    {
        $departments = Department::all();

        return response()->json($departments);
    }

    public function getDataEmpFromApi($page = 1)
    {
        try {

            // Request headers
            $headers = [
                'Authorization' => $this->token(),
                // Add any other headers as needed
            ];

            $queryParam = [
                'filter[IsActive]' => 'true',
                'paginate' => $this->paginate,
                'include' => 'positions.organization.manager,organizations,workLocations',
                'page' => $page
            ];

            // Make a GET request with headers and body
            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders($headers)->get($this->apiUrl, $queryParam);

            // Process the API response (e.g., decode JSON)
            $responseData = $response->json();
            //dd($responseData['data'][0]['work_location'][0]['location']['phone_number']);
            // Process the data in the current page
            foreach ($responseData['data'] as $key => $data) {
                $currentDateTime = now(); // Use Laravel's helper function to get the current date and time
                $newEmployeeData = [
                    'EMPLOYEE_CODE' => isset($data['person_code']) ? sprintf("%08d", $data['person_code']) : null,
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
                    'JOB_CODE' => isset($data['positions']) ? $data['positions'][0]['PST_TShortName'] ?? null : null,
                    'JOB_NAME' => isset($data['positions']) ? $data['positions'][0]['PST_TLongName'] ?? null: null,
                    'TEL' => isset($data['work_location']) ? $data['work_location'][0]['location']['phone_number'] ?? null: null,
                    'CREATE_DATE' => $currentDateTime,
                    'CREATE_BY' => 'system',
                    'UPDATE_DATE' => $currentDateTime,
                    'UPDATE_BY' => 'system',
                    'FLAG_DELETE' => 'N',
                    'FLAG_ACTIVE' => 'Y',
                    // Add other fields as needed
                ];
                // Before calling updateOrCreate(), log the data being passed
                \Log::info('Data to be updated or created', $newEmployeeData);
                // Insert data into the employee table using Eloquent
                //Employee::create($newEmployeeData);

                // Update or create the employee
                $employee = Employee::updateOrCreate(
                    ['EMPLOYEE_CODE' => sprintf("%08d", $data['person_code'])],
                    $newEmployeeData
                );
                $logData = $employee->toArray();
                // Log or dump the employee instance
                \Log::info('Employee updated or created', $logData);

                // INNITIATOR
                EmployeeRole::updateOrCreate(
                    [
                        'EMPLOYEE_CODE' =>  sprintf("%08d", $data['person_code']),
                        'ROLE_CODE' => 'INNITIATOR',
                    ],
                    [
                        'EMPLOYEE_CODE' =>  sprintf("%08d", $data['person_code']),
                        'ROLE_CODE' => 'INNITIATOR',
                        'CREATE_DATE' => $currentDateTime,
                        'CREATE_BY' => 'system',
                        'UPDATE_DATE' => $currentDateTime,
                        'UPDATE_BY' => 'system',
                    ]
                );
                // PSSRLEAD
                EmployeeRole::updateOrCreate(
                    [
                        'EMPLOYEE_CODE' =>  sprintf("%08d", $data['person_code']),
                        'ROLE_CODE' => 'PSSRLEAD',
                    ],
                    [
                        'EMPLOYEE_CODE' =>  sprintf("%08d", $data['person_code']),
                        'ROLE_CODE' => 'PSSRLEAD',
                        'CREATE_DATE' => $currentDateTime,
                        'CREATE_BY' => 'system',
                        'UPDATE_DATE' => $currentDateTime,
                        'UPDATE_BY' => 'system',
                    ]
                );
                // PSSRTEAM
                EmployeeRole::updateOrCreate(
                    [
                        'EMPLOYEE_CODE' =>  sprintf("%08d", $data['person_code']),
                        'ROLE_CODE' => 'PSSRTEAM',
                    ],
                    [
                        'EMPLOYEE_CODE' =>  sprintf("%08d", $data['person_code']),
                        'ROLE_CODE' => 'PSSRTEAM',
                        'CREATE_DATE' => $currentDateTime,
                        'CREATE_BY' => 'system',
                        'UPDATE_DATE' => $currentDateTime,
                        'UPDATE_BY' => 'system',
                    ]
                );
            }

            // Return success response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection error, invalid response)
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }


    public function getTotalData()
    {

        $headers = [
            'Authorization' => $this->token(),
        ];
        $queryParam = [
            'filter[IsActive]' => 'true',
            'paginate' => $this->paginate,
            'include' => 'positions.organization.manager,organizations,workLocations',
        ];
        try {
            //code...

            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders($headers)->get($this->apiUrl, $queryParam);

            // Process the API response (e.g., decode JSON)
            $responseData = $response->json();
            // Initialize variables
            $last_page = $responseData['meta']['last_page'];
            $total = $responseData['meta']['total'];

            // FLAG_ACTIVE N
            Employee::query()->update([
                'FLAG_DELETE' => 'Y',
                'FLAG_ACTIVE' => 'N',
            ]);

            return response()->json([
                'last_page' => $last_page,
                'paginate' => $this->paginate,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
