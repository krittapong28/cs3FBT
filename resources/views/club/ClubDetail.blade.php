@extends('layouts.main')

@section('content')
 
<div class="club-detail">
  <div class="container">
    <div class="card">
      <div class="content-body">
        <div class="row">
          <div class="col-lg-3">
            <div class="img_club">
              <img src="/asset/img/logo_footclub_400px.png" alt="">
            </div> 
          </div>
          <div class="col-lg-9">
            <div class="club-details-list">
              <div class="club-name m-b-20">
                <h4>{{ $club->TeamName }}dd</h4>
              </div> 
              <div class="row">
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">President</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $club->President }}</label></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Staffs</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $club->TeamName }}</label></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Head coach</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $club->TeamName }}</label></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Avr. age</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $averageAge }}</label></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Players</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $playerCount }}</label></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">City</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $club->TeamName }}</label></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Foreigners</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $foreignerCount }}</label></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Stadium</label></div>
                <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $club->TeamName }}</label></div>
                <!-- //////////////////////////////////////// --> 
              </div>
            </div> 
          </div>
        </div> 
      </div> 
    </div> 

    <div class="card">
      <div class="content-body">
        <ul class="nav nav-tabs" id="clubTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="players-tab" data-bs-toggle="tab" data-bs-target="#players" type="button" role="tab" aria-controls="players" aria-selected="true" style="color: #1a237e;">Players</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff" type="button" role="tab" aria-controls="staff" aria-selected="false" style="color: #1a237e;">Staff</button>
          </li>
        </ul>
        <div class="tab-content" id="clubTabContent">
          <div class="tab-pane fade show active" id="players" role="tabpanel" aria-labelledby="players-tab">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead style="background-color: #1a237e; color: white;">
                  <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Nationality</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($players as $player)
                  <tr>
                    <td>
                      <div class="img-team">
                        <img src="{{ asset('storage/Player/' . $player->Img) }}" alt="" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #1a237e;">
                      </div>
                    </td>
                    <td style="color: #1a237e; font-weight: 500;">{{ $player->FirstName }} {{ $player->LastName }}</td>
                    <td style="color: #424242;">{{ $player->PositionName }}</td>
                    <td style="color: #424242;">{{ $player->Nationality }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="staff" role="tabpanel" aria-labelledby="staff-tab">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead style="background-color: #1a237e; color: white;">
                  <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Nationality</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($staffs as $staff)
                  <tr>
                    <td>
                      <div class="img-team">
                        <img src="{{ asset('storage/Staff/' . $staff->Img) }}" alt="" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #1a237e;">
                      </div>
                    </td>
                    <td style="color: #1a237e; font-weight: 500;">{{ $staff->FirstName }} {{ $staff->LastName }}</td>
                    <td style="color: #424242;">{{ $staff->PositionName }}</td>
                    <td style="color: #424242;">{{ $staff->Nationality }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
     
  </div>
 </div> 
@endsection
