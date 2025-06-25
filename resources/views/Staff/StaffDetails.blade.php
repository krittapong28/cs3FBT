@extends('layouts.main')

@section('content')
<div class="club-detail">
  <div class="container">
    <div class="card">
      <div class="content-body">
        <div class="form-check form-switch">
          <span class="form-check-label" for="flexSwitchCheckChecked">Team</span>  
          <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
        </div>
        
        <div class="row">
          <div class="col-lg-3">
            <div class="img_club">
              <img src="{{ asset('storage/Staff/' . $Staff->Img) }}" style="width: 150px;"> 
            </div>
          </div>

          @php
            $mainPositionCode = null;
            $alternatePositionCodes = [];

            foreach ($Position as $posData) {
              if ($posData->FlagMain == 'Y') { 
                $mainPositionCode = $posData->PositionName;
              } else if ($posData->FlagMain == 'N') {
                $alternatePositionCodes[] = $posData->PositionName;
              }
            }
          @endphp

          <div class="col-lg-9">
            <div class="club-details-list">
              <div class="club-name m-b-20">
                <h4>{{ $Staff->FirstName }} {{ $Staff->LastName }}</h4>
              </div>
              
              <div class="row">
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Main Position</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $mainPositionCode ?? 'N/A' }}</label></div>
                
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Alternate Position</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">
                  {{ !empty($alternatePositionCodes) ? implode(', ', $alternatePositionCodes) : 'N/A' }}
                </label></div>

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Club</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $Staff->Club ?? 'N/A' }}</label></div>

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">DOB</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $Staff->DOB }}</label></div>

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Nationality</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $Staff->Nationality }}</label></div>

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Age</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ \Carbon\Carbon::parse($Staff->DOB)->age }}</label></div>

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Height</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $Staff->Height }}</label></div>

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Foot</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $Staff->Foot }}</label></div>

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Salary</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ number_format($Staff->Salary, 2) }}</label></div>

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Expected Salary</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ number_format($Staff->ExpectedSalary, 2) }}</label></div>

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Agent</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $Staff->Agent ?? 'N/A' }}</label></div>

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Clips</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $Staff->Clips ?? 'N/A' }}</label></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="content-body">
        <div class="row">
          <div class="col-lg-4 offset-lg-3">
            <div class="club-name m-b-20">
              <h4>Career History</h4>
            </div>
            <div class="row">
              @for ($year = date('Y'); $year >= date('Y') - 4; $year--)
                <div class="col-lg-3">
                  <label class="club-details-toppic m-b-10">{{ $year }}</label>
                </div>
                <div class="col-lg-9">
                  <label class="club-details-text m-b-10">{{ $Staff->{"Career$year"} ?? 'N/A' }}</label>
                </div>
              @endfor
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 