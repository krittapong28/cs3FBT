<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmployeeRole;
use App\Models\Menu;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrganizationDataController extends Controller
{
    private $apiUrl = 'https://hrapi.egat.co.th/api/v1/organizations';
    private $paginate = '100';


    private function token()
    {
        return config('app.token_hr');
    }

    public function getTotalData()
    {

        $headers = [
            'Authorization' => $this->token(),
        ];
        $queryParam = [
            'paginate' => $this->paginate,
            'include' => 'manager',
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

            return response()->json([
                'last_page' => $last_page,
                'paginate' => $this->paginate,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    public function getDataOrgFromApi($page)
    {
        try {

            $headers = [
                'Authorization' => $this->token(),
            ];

            $queryParam = [
                'paginate' => $this->paginate,
                'include' => 'manager',
                'page' => $page
            ];

            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders($headers)
                ->get($this->apiUrl, $queryParam);

            $responseData = $response->json();
            foreach ($responseData['data'] as $data) {
                $currentDateTime = now(); // Use Laravel's helper function to get the current date and time
                $newOrgData = [
                    'ORG_CODE' => $data['org_code'] ?? null,
                    'ORG_NAME' => $data['org_thai_shortname'] ?? null,
                    'ORG_DESC' => $data['org_thai_longname'] ?? null,
                    'SITE_CODE' => $data['org_cost_center_code'] ?? null,
                    'ORG_LEVEL' => $data['org_level'] ?? null,
                    'ORG_THAI_NAME_PATH' => $data['org_thai_name_path'] ?? null,
                    'MANAGER_CODE' => isset($data['manager']) ? sprintf("%08d", $data['manager']['person_code']) : null,
                    'MANAGER_NAME' => isset($data['manager']) ? $data['manager']['person_thai_name'] : null,
                    'ORG_GROUP_LEVEL' => intval($data['org_group_level'] ?? 0),
                    'CREATE_DATE' => $currentDateTime,
                    'CREATE_BY' => 'system',
                    'UPDATE_DATE' => $currentDateTime,
                    'UPDATE_BY' => 'system',
                    'FLAG_DELETE' => 'N',
                    'FLAG_ACTIVE' => 'Y',
                ];

                \Log::info('Data to be updated or created', $newOrgData);
                // Update or create the Organization
                $org = Organization::updateOrCreate(
                    ['ORG_CODE' => $data['org_code']],
                    $newOrgData
                );
                $logData = $org->toArray();
                // Log or dump the Organization instance
                \Log::info('Organization updated or created', $logData);

                // approver
                if (isset($data['manager'])) {
                    EmployeeRole::updateOrCreate(
                        [
                            'EMPLOYEE_CODE' =>  sprintf("%08d", $data['manager']['person_code']),
                            'SITE_CODE' => $data['org_code'],
                        ],
                        [
                            'EMPLOYEE_CODE' =>  sprintf("%08d", $data['manager']['person_code']),
                            'SITE_CODE' => $data['org_code'],
                            'ROLE_CODE' => 'APPROVER',
                            'CREATE_DATE' => $currentDateTime,
                            'CREATE_BY' => 'system',
                            'UPDATE_DATE' => $currentDateTime,
                            'UPDATE_BY' => 'system',
                        ]
                    );
                }
            }

            // Return success response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection error, invalid response)
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
