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
                 <img src="{{ asset('storage/Player/' . $player->Img) }}" style="width: 150px;"> 
                 
              </div>
            </div>
              @php
                // เตรียมตัวแปรสำหรับตำแหน่งหลักและสำรอง
                $mainPositionCode = null;
                $alternatePositionCodes = [];

                // วนลูป Collection $Position ที่ได้จาก Controller
                // เพื่อแยกตำแหน่งหลักและตำแหน่งสำรอง
                foreach ($Position as $posData) {
                    if ($posData->FlagMain == 'Y') { 
                        $mainPositionCode = $posData->Positioncode;
                    } else if ($posData->FlagMain == 'N') { // ถ้าไม่ใช่ คือตำแหน่งสำรอง
                        $alternatePositionCodes[] = $posData->Positioncode;
                    }
                    
                }
            @endphp
            <div class="col-lg-9">
              <div class="club-details-list">
                <div class="club-name m-b-20">
                  <h4>{{ $player->FirstName }} {{ $player->LastName }}</h4>
                </div>
                <div class="row">
                  
                                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Main Position</label></div>
                                <div class="col-lg-3"><label class="club-details-text m-b-10">
                                    {{ $mainPositionCode ?? 'N/A' }} 
                                </label></div>
                                <div class="col-lg-3"><label class="club-details-toppic m-b-10">Alternate Position</label></div>
                                <div class="col-lg-3"><label class="club-details-text m-b-10">
                                    {{ !empty($alternatePositionCodes) ? implode(', ', $alternatePositionCodes) : 'N/A' }}
                         
                                </label></div>
                          
                  <div class="col-lg-3"><label class="club-details-toppic m-b-10">Club</label></div>
                  <div class="col-lg-3"><label class="club-details-text m-b-10">xxxxx</label></div>
                  <div class="col-lg-3"><label class="club-details-toppic m-b-10">DOB</label></div>
                  <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $player->DOB }}</label></div>
                  <div class="col-lg-3"><label class="club-details-toppic m-b-10">Nationality</label></div>
                  <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $player->Nationality }}</label></div>
                  <div class="col-lg-3"><label class="club-details-toppic m-b-10">Age</label></div>
                  <div class="col-lg-3"><label class="club-details-text m-b-10">{{ \Carbon\Carbon::parse($player->DOB)->age }}</label></div>
                  <div class="col-lg-3"><label class="club-details-toppic m-b-10">Height</label></div>
                  <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $player->Height }}</label></div>
                  <div class="col-lg-3"><label class="club-details-toppic m-b-10">Foot</label></div>
                  <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $player->Foot }}</label></div>
                  <div class="col-lg-3"><label class="club-details-toppic m-b-10">Salary</label></div>
                  <div class="col-lg-3"><label class="club-details-text m-b-10">{{ number_format($player->Salary, 2) }}</label></div>
                  <div class="col-lg-3"><label class="club-details-toppic m-b-10">Expected Salary</label></div>
                  <div class="col-lg-3"><label class="club-details-text m-b-10">{{ $player->ExpectedSalary }}</label></div>
                  <div class="col-lg-3"><label class="club-details-toppic m-b-10">Agent</label></div>
                  <div class="col-lg-3"><label class="club-details-text m-b-10">xxxxx</label></div>
                  <div class="col-lg-3"><label class="club-details-toppic m-b-10">Clips</label></div>
                  <div class="col-lg-3"><label class="club-details-text m-b-10">xxxxx</label></div>
                  <!-- //////////////////////////////////////// -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- <div class="card">
        <div class="content-body">
          <div class="row">
            <div class="form-check form-switch">
              <label class="form-check-label" for="flexSwitchCheckChecked">Team</label> 
              <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
              
            </div>
            <div class="col-lg-3">
              <div class="img_club">
                <img src="/asset/img/595.jpg" alt="">
              </div>
            </div>
            <div class="col-lg-9">
              <div class="club-details-list">
                <div class="club-name m-b-20">
                  <h4>THEERAPONG JANTHAKEAW</h4>
                </div>
                <div class="row">
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">Main Position</label></div>
                  <div class="col-lg-6"> 
                    <select class="form-select" id="inputGroupSelect01">
                      <option selected>Choose...</option>
                      <option value="1">One</option>
                      <option value="2">Two</option>
                      <option value="3">Three</option>
                    </select>
                  </div>
                  <style>
                    .position-group {
                      display: grid;
                      grid-template-columns: repeat(4, auto);
                      gap: 8px 20px; 
                      padding: 10px;
                      padding-left: 15px;
                      max-width: 300px;
                    }
                  
                    /* .position-group label {
                      font-weight: bold;
                      color: #2e6b2e;
                    } */
                  </style>
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">Alternate Position</label></div>
                  <div class="position-group">
                    <div><input type="checkbox" id="position1" name="position" value="GK"> <label for="position1">GK</label></div>
                    <div><input type="checkbox" id="position2" name="position" value="CB"> <label for="position2">CB</label></div>
                    <div><input type="checkbox" id="position3" name="position" value="LB"> <label for="position3">LB</label></div>
                
                    <div><input type="checkbox" id="position4" name="position" value="RB"> <label for="position4">RB</label></div>
                    <div><input type="checkbox" id="position5" name="position" value="CDM"> <label for="position5">CDM</label></div>
                   
                
                    <div><input type="checkbox" id="position7" name="position" value="CAM"> <label for="position7">CAM</label></div>
                    <div><input type="checkbox" id="position8" name="position" value="RW"> <label for="position8">RW</label></div>
                    <div><input type="checkbox" id="position9" name="position" value="LW"> <label for="position9">LW</label></div>
                
                    <div><input type="checkbox" id="position10" name="position" value="ST"> <label for="position10">ST</label></div>
                  </div>
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">Club</label></div>
                  <div class="col-lg-6"> <input type="text" class="form-control"> </div>
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">DOB</label></div>
                  <div class="col-lg-6"> <input type="text" class="form-control"> </div>
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">Nationality</label></div>
                  <div class="col-lg-6"> <select class="form-select" id="inputGroupSelect01">
                    <option selected>Choose...</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select> </div>
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">Age</label></div>
                  <div class="col-lg-6"> <input type="text" class="form-control"> </div>
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">Height</label></div>
                  <div class="col-lg-6"> <input type="text" class="form-control"> </div>
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">Foot</label></div>
                  <div class="col-lg-6"> <select class="form-select" id="inputGroupSelect01">
                    <option selected>Choose...</option>
                    <option value="1">ซ้าย</option>
                    <option value="2">ขวา</option>
                    <option value="3">ทั้งสอง</option>
                  </select></div>
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">Salary</label></div>
                  <div class="col-lg-6"> <input type="text" class="form-control"> </div>
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">Expected Salary</label></div>
                  <div class="col-lg-6"> <input type="text" class="form-control"> </div>
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">Agent</label></div>
                  <div class="col-lg-6"> <input type="text" class="form-control"> </div>
                  <div class="col-lg-6"><label class="club-details-toppic m-b-10">Clips</label></div>
                  <div class="col-lg-6"> <input type="text" class="form-control"> </div>
                 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->

      <div class="card">
        <div class="content-body">
          <div class="row">
            <div class="col-lg-4 offset-lg-3">
              <div class="club-name m-b-20">
                <h4>Playing Career</h4>
              </div>
              <div class="row">

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">2025</label></div>
                <div class="col-lg-9"><label class="club-details-text m-b-10">xxxxx</label></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">2024</label></div>
                <div class="col-lg-9"><label class="club-details-text m-b-10">xxxxx</label></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">2023</label></div>
                <div class="col-lg-9"><label class="club-details-text m-b-10">xxxxx</label></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">2022</label></div>
                <div class="col-lg-9"><label class="club-details-text m-b-10">xxxxx</label></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">2021</label></div>
                <div class="col-lg-9"><label class="club-details-text m-b-10">xxxxx</label></div>
              </div>
            </div>
<style>
  .coloractive{
        background-color: green;
  }
  .coloractive-sec{
        background-color: red;
  }
</style>

 
            <div class="col-lg-5">
              <div class="position-picture">
                <div class="field-container">
                <img src="{{ asset('storage/stadium_2.png') }}" alt="">
                 
     

            {{-- วนลูปผ่านชื่อตำแหน่งมาตรฐานทั้งหมด เพื่อวางบนสนาม --}}
            @foreach(['GK','CB','LB','RB','CDM','CM','CAM','RW','LW','ST'] as $pos)
                @php
                    // ตรวจสอบว่าเป็นตำแหน่งหลักหรือไม่
                    $isMain = ($mainPositionCode === $pos);
                    // ตรวจสอบว่าเป็นตำแหน่งสำรองหรือไม่
                    $isAlt = in_array($pos, $alternatePositionCodes);
                @endphp

                @if($isMain || $isAlt)
                    {{-- กำหนด Class CSS ตามว่าเป็นตำแหน่งหลักหรือสำรอง --}}
                    <div class="player position-{{ $pos }} {{ $isMain ? 'coloractive' : 'coloractive-sec' }}">
                        {{ $pos }}
                    </div>
                @endif
            @endforeach
          
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="text-center">
                <div><button class="btn-d btn-contact">Contact</button></div>
                <div><button class="btn-d btn-save">Save</button></div>
              </div>

            </div>


          </div>
        </div>
      </div>
      <!-- <div class="card">
        <div class="content-body">
          <div class="row">
            <div class="col-lg-4 offset-lg-3">
              <div class="club-name m-b-20">
                <h4>Playing Career</h4>
              </div>
              <div class="row">

                <div class="col-lg-3"><label class="club-details-toppic m-b-10">2025</label></div>
                <div class="col-lg-9"> <input type="text" class="form-control"></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">2024</label></div>
                <div class="col-lg-9"> <input type="text" class="form-control"></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">2023</label></div>
                <div class="col-lg-9"> <input type="text" class="form-control"></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">2022</label></div>
                <div class="col-lg-9"> <input type="text" class="form-control"></div>
                <div class="col-lg-3"><label class="club-details-toppic m-b-10">2021</label></div>
                <div class="col-lg-9"> <input type="text" class="form-control"></div>
              </div>
            </div>



            <div class="col-lg-5">
              <div class="position-picture">
                <div class="field-container">
                  <img src="/asset/img/stadium_2.png" alt="">
                  <div class="player position-GK">GK</div>
                  <div class="player position-CB">CB</div>
                  <div class="player position-LB">LB</div>
                  <div class="player position-RB">RB</div>
                  <div class="player position-CDM">CDM</div>
                  <div class="player position-CM">CM</div>
                  <div class="player position-CAM">CAM</div>
                  <div class="player position-RW">RW</div>
                  <div class="player position-LW">LW</div>
                  <div class="player position-ST">ST</div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="text-center">
                <div><button class="btn-d btn-contact">Contact</button></div>
                <div><button class="btn-d btn-save">Save</button></div>
              </div>

            </div>


          </div>
        </div>
      </div> -->
    </div>
  </div>
@endsection
