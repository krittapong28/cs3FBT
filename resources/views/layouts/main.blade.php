<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <title>Hello, world!</title>
  </head>
  <body>
    <style>
   
    </style>
    <div class="nav_bar_status">
      <div class="container">
     <div class="nav_bar_status_wrap">
        <div class=" d-flex">
          <p>Username : xxxxxxxxxxxxxx</p>
          <span><p class="m-l-10">role : xxxxxxxxxxxxxx</p></span>
          <div class="ms-auto">
            <p>Logout</p>
          </div>
        </div> 
      </div>
      </div> 
    </div>
    <div class="nav_bar_bg">
    <div class="container">
      <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="{{ route("clublist") }}">Clubs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route("player") }}">Players</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('staff.index') }}">Staffs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Contact us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">About</a>
            </li>
            <!-- <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Dropdown
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
              </ul>
            </li> -->
            <!-- <li class="nav-item">
              <a class="nav-link disabled" aria-disabled="true">Disabled</a>
            </li> -->
          </ul>
          <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
        </div>
      </div>
    </nav>

    </div>
  </div>
  <div class="p-5   bg_img  ">
    <div class="container py-5 text-center">
      @if(Route::currentRouteName() == 'clublist')
        <h1 class="display-5 fw-bold">CLUB</h1>
      @elseif(Route::currentRouteName() == 'player' || Route::currentRouteName() == 'player.index')
        <h1 class="display-5 fw-bold">PLAYER</h1>
      @elseif(Route::currentRouteName() == 'Staff.index' || Route::currentRouteName() == 'staff.index')
        <h1 class="display-5 fw-bold">STAFF</h1>
      @elseif(Route::currentRouteName() == 'team')
        <h1 class="display-5 fw-bold">TEAMS</h1>
      @elseif(Route::currentRouteName() == 'welcome')
        <h1 class="display-5 fw-bold">WELCOME</h1>
      @else
        <h1 class="display-5 fw-bold">CLUB</h1>
      @endif
      
    
    </div>
  </div>
  <style>
 
  </style>
            <!-- End of Header -->


            

            <!-- Content Wrapper -->
            <div class="club-content">
            <div class="container">
                @yield('content')
            </div>
            </div>
            <!-- End of Content Wrapper -->

<div class="footer">
  <div class="container">
    <ul class="d-flex">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="#">Clubs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Players</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Staffs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact us</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">About</a>
      </li>
      <!-- <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Dropdown
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Action</a></li>
          <li><a class="dropdown-item" href="#">Another action</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#">Something else here</a></li>
        </ul>
      </li> -->
      <!-- <li class="nav-item">
        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
      </li> -->
    </ul>

  </div>
 </div>
    
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->

    </body>

</html>
