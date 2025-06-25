@extends('layouts.main')

@section('content')

    <!-- Page Detail -->
    <div class="card">
       <div class="header_menu">
      <h3>Search Clubs </h3> 
      <hr>
    </div>
    <div class="content-body">
    <form id="frmSearchLeague" method="" action="" enctype="multipart/form-data">
    @csrf
      <div class="row">
        <label class="col-lg-2"> League</label>
        <div class="col-lg-4">
          <select class="form-select" id="ddlLeagueSearch" name="ddlLeagueSearch">
            <option selected>Choose...</option>
            @foreach ($leaguelist as $league)
            <option value="{{ $league->LeagueCode }}"{{ request()->input('ddlLeagueSearch') === $league->LeagueCode ? ' selected' : '' }}>{{ $league->LeagueName }}</option>
            @endforeach
          </select>
        </div>
        <div class="m-t-20">
        <div class="text-center">
          <button class="btn-d btn-search">Search</button>
        </div>
      </div>
    </form>
    </div>
    </div>
    
    <div class="card m-b-50">
      <div class="content-body">
      <div class="row">
      @foreach ($clublist as $club)
      <div class="col-lg-4">
        <a href="{{ route('ClubxDetail', $club->Id) }}">
        <div id="parent" class="card card-img" style="background-image: url('{{ asset('storage/view-empty-football-stadium.jpg' ) }}');">
          <div class="img-team">
          <img src="{{ asset('storage' . $club->Img) }}" alt="">
          </div>
           
          <div class="team-name"><h6>{{ $club->TeamName }}</h6> </div>
        
          <div id="hover-content">
            <h4>{{ $club->TeamName }}</h4>
            <p>Former President: {{ $club->President }}</p>
            <p>Manager Name: {{ $club->Manager }}</p>
            <p>Past Coach: {{ $club->PastCoach }}</p>
            <p>Current Coach: {{ $club->CurrentCoach }}</p>
            <p>Location: {{ $club->CityCode }}</p>
          </div>
        </div> 
       </a>
      </div>
      @endforeach
    </div>
    <div class="text-center">
      <button class="btn-d btn-viewall">view all</button>
    </div>
  </div>
    </div>
@endsection
