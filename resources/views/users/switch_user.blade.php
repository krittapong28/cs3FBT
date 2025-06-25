@extends('layouts.main')
@section('content')


<div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h1 class="h5 mb-0 text-gray-800">เปลี่ยนผู้ใช้งาน</h1>
</div>

<div class="card border-0 shadow mb-5">
    <div class="card-body ">

    <form id="frmSwitch"  method="POST" action="{{ route('switch_user') }}" enctype="multipart/form-data">
        @csrf
            <div class="row  px-4 m-0">
                <div class="col-md-4">
                    <select class="form-control" name="ddlEmpCode">
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->EMPLOYEE_CODE }}">{{ $employee->EMPLOYEE_CODE }}</option>
                            {{--<option value="{{ $employee->EMPLOYEE_CODE }}">{{ $employee->PREFIX_NAME }}{{ $employee->FIRST_NAME }} {{ $employee->LAST_NAME }}</option>--}}
                        @endforeach
                    </select>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-primary" name="btnSubmitSwitch" >บันทึก</button>
                </div>
            </div>
            
    </form>

    </div>

</div>





@endsection
