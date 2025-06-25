@extends('layouts.main')

@section('content')

<!-- Page Detail -->
<div class="card">
  <div class="header_menu">
    <h3>Search Staff</h3>
    <hr>
  </div>
  <div class="content-body">
    <form id="frmSearchStaff" method="GET" action="{{ route('staff.index') }}" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <label class="col-lg-2">Position</label>
        <div class="col-lg-4">
          <select class="form-select" id="ddlPlayerSearch" name="ddlPlayerSearch">
            <option value="" {{ empty($selectedPositionCode) ? 'selected' : '' }}>All</option>
            @foreach($Positionlist as $position)
              <option value="{{ $position->Positioncode }}" {{ $selectedPositionCode == $position->Positioncode ? 'selected' : '' }}>
                {{ $position->PositionName }}
              </option>
            @endforeach
          </select>
        </div>

        <label class="col-lg-2">Age</label>
        <div class="col-lg-4">
          <select class="form-select" id="ddlAgeSearch" name="ddlAgeSearch">
            <option value="">ALL</option>
            <option value="1" {{ $selectedAge == '1' ? 'selected' : '' }}>Under 15</option>
            <option value="2" {{ $selectedAge == '2' ? 'selected' : '' }}>Under 16</option>
            <option value="3" {{ $selectedAge == '3' ? 'selected' : '' }}>Under 17</option>
            <option value="4" {{ $selectedAge == '4' ? 'selected' : '' }}>Under 18</option>
            <option value="5" {{ $selectedAge == '5' ? 'selected' : '' }}>Under 19</option>
            <option value="6" {{ $selectedAge == '6' ? 'selected' : '' }}>Under 20</option>
            <option value="7" {{ $selectedAge == '7' ? 'selected' : '' }}>Under 21</option>
            <option value="8" {{ $selectedAge == '8' ? 'selected' : '' }}>Under 22</option>
            <option value="9" {{ $selectedAge == '9' ? 'selected' : '' }}>Under 23</option>
            <option value="10" {{ $selectedAge == '10' ? 'selected' : '' }}>23-30</option>
            <option value="11" {{ $selectedAge == '11' ? 'selected' : '' }}>31-36</option>
          </select>
        </div>

        <label class="col-lg-2">Salary min</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" id="ddlsalaryminSearch" name="ddlsalaryminSearch" value="{{ request('ddlsalaryminSearch') }}">
        </div>

        <label class="col-lg-2">Salary max</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" id="ddlsalarymaxSearch" name="ddlsalarymaxSearch" value="{{ request('ddlsalarymaxSearch') }}">
        </div>

        <div class="m-t-20">
          <div class="text-center">
            <button type="submit" class="btn-d btn-search">Search</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="content-body">
    <div class="row">
      @foreach ($playerlist as $staff)
        <div class="col-lg-3">
          <a href="{{ route('StaffDetails', $staff->Id) }}">
            <div id="parent" class="card card-img" style="background-image: url('{{ asset('storage/view-empty-football-stadium.jpg') }}');">
              <div class="img-team">
                <img src="{{ asset('storage/Staff/' . $staff->Img) }}" alt=""> 
              </div>
              <div class="team-name">
                <h6>{{ $staff->FirstName }} {{ $staff->LastName }}</h6>
              </div>
              <div id="hover-content">
                <p>Nationality: {{ $staff->Nationality }}</p>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
    
    <div class="text-center">
      <button type="button" class="btn btn-primary">View All</button>
    </div>
  </div>
</div>
@endsection 