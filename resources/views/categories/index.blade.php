@extends('layouts.main')
@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h1 class="h5 mb-0 text-gray-800">จัดการ MOC Category</h1>
</div>

<div class="card border-0 shadow mb-5">
    <div class="card-body ">

        <div class="d-flex justify-content-between align-items-center">
            <strong>รายการ MOC Category</strong>
            {{-- <button class="btn btn-outline-primary rounded-pill btn-sm" onclick="getSearch()"><i class="fa fa-filter"></i> ตัวกรอง</button> --}}
        </div>

        <button type="submit" class="btn btn-outline-primary rounded-pill mt-1 mb-2 " onclick="getCreate();">เพิ่ม MOC Category</button>

        <div class="table-responsive">
            <table class="table table-borderless ">
                <thead class="bg-primary thead text-white header">
                    <tr>
                        <th scope="col" class="text-center rounded-first">ลำดับ</th>
                        <th class="text-center" scope="col">สัญลักษณ์</th>
                        <th class="text-left sticky-column" scope="col">ชื่อ MOC Category</th>
                        <th class="text-center" scope="col">สถานะ</th>
                        <th scope="col">สร้างโดย</th>
                        <th class="text-center" scope="col">วันที่สร้าง</th>
                        <th scope="col">แก้ไขโดย</th>
                        <th class="text-center" scope="col">วันที่แก้ไข</th>
                        <th scope="col" class="rounded-last"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cate)
                        <tr>
                            <td scope="row" class="text-center">{{ $categories->firstItem()+$loop->index }}</td>
                            <td class="text-center">
                                <div class="thumbnail text-center" style="position: center;">
                                    @if($cate->FILE_VALUE)
                                    <img src="storage/Category/{{ $cate->FILE_VALUE }}" class="rounded" alt="ภาพสัญลักษณ์" style="width:20px">
                                    @endif
                                </div>
                            </td>
                            <td class="text-left sticky-column">{{ $cate->CATEGORY_NAME }}</td>
                            <td class="text-center p-2 m-0">
                                @if($cate->FLAG_ACTIVE === 'Y')
                                    <div class="alert alert-success small m-0 p-1 text-center" style="max-width: 140px; max-height:30px;">เปิดการใช้งาน</div>
                                @else
                                    <div class="alert alert-danger small m-0 p-1 text-center" style="max-width: 140px; max-height:30px;">ปิดการใช้งาน</div>
                                @endif
                            </td>
                            <td>{{ $cate->CREATE_BY }}</td>
                            <td class="text-center">{{ $cate->CREATE_DATE ? Carbon\Carbon::parse($cate->CREATE_DATE)->addYears(543)->format('d/m/Y, H:i') : '' }}</td>
                            <td>{{ $cate->UPDATE_BY }}</td>
                            <td class="text-center">{{ $cate->UPDATE_DATE ? Carbon\Carbon::parse($cate->UPDATE_DATE)->addYears(543)->format('d/m/Y, H:i') : '' }}</td>
                            <td>
                                <button class="btn btn-white p-0" onclick="getEdit({{ $cate->ID }})"><i class="far fa-edit text-primary"></i></button>
                                <button class="btn btn-white  p-0" onclick="confirmDelete({{ $cate->ID }},'{{ $cate->CATEGORY_NAME }}')"><i class="far fa-trash-alt text-danger mr-1"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center">      
            <p>แสดง {{ $categories->firstItem() }}-{{ $categories->lastItem() }} รายการ จาก {{ $categories->total() }} รายการ</p>

            <div class="d-flex justify-content-end">
                <a class="btn btn-primary btn-circle btn-sm @if($categories->onFirstPage()) disabled @endif" href="{{ $categories->previousPageUrl() }}" aria-label="ก่อนหน้า" ><i class="fa fa-chevron-left"></i></a>
                <div class="d-flex align-items-center">
                    <p class="m-0 ml-1 mr-1">Page </p>
                    <div class="dropdown">
                        <button class="dropdown-toggle btn btn-outline-secondary btn-sm" type="button" id="pageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @if ($categories->lastPage() <= 1) disabled @endif>
                            {{ $categories->currentPage() }}
                        </button>
                        <div class="dropdown-menu scrollable-dropdown" aria-labelledby="pageDropdown">
                            @for ($page = 1; $page <= $categories->lastPage(); $page++)
                                <a class="dropdown-item" href="{{ $categories->url($page) }}">{{ $page }}</a>
                            @endfor
                        </div>
                    </div>
                    <p class="m-0 ml-1 mr-1"> Of {{ $categories->lastPage() }}</p>
                </div>
                <a class="btn btn-primary btn-sm btn-circle @if(!$categories->hasMorePages()) disabled @endif" role="button" href="{{ $categories->nextPageUrl() }}" aria-label="ถัดไป"><i class="fa fa-chevron-right"></i></a>
            </div>
        </div>

    </div>
</div>

@include('categories.create_modal')
@include('categories.edit_modal')
@include('categories.search_modal')

<script>
    function getCreate() {
        $('#thumbCategoryContainer').hide()
        $('#fileBG').show();
        $('#frmCreate').trigger("reset");
        $("#mdlCreate").modal()
    }
</script>

<script>
    function getSearch() {
        $("#mdlSearch").modal()
    }
</script>

<script>
    const chkFlagActive = document.querySelector('#chkFlagActive');
    const txtchkFlagActive = document.querySelector('#txtchkFlagActive');  
    chkFlagActive.addEventListener('change', function() {
        if (this.checked) {
            txtchkFlagActive.textContent = 'เปิดการใช้งาน';
        } else {
            txtchkFlagActive.textContent = 'ปิดการใช้งาน';
        }
    });

    const chkFlagActiveUpdate = document.querySelector('#chkFlagActiveUpdate');
    const txtchkFlagActiveUpdate = document.querySelector('#txtchkFlagActiveUpdate');   
    chkFlagActiveUpdate.addEventListener('change', function() {
        if (this.checked) {
            txtchkFlagActiveUpdate.textContent = 'เปิดการใช้งาน';
        } else {
            txtchkFlagActiveUpdate.textContent = 'ปิดการใช้งาน';
        }
    });
</script>

<script>
    const fileName = document.querySelector('#fileName');  
    fileName.addEventListener('change', function() {
        const file = this.files[0]; // เลือกไฟล์ที่ผู้ใช้เลือก
        // ตรวจสอบนามสกุลของไฟล์
        const fileNameParts = file.name.split('.');
        const fileExtension = fileNameParts[fileNameParts.length - 1].toLowerCase();
        if (fileExtension !== 'jpg' && fileExtension !== 'png') {
            // แจ้งเตือนถ้าไฟล์ไม่ใช่ JPG หรือ PNG
            alert('โปรดเลือกไฟล์รูปภาพที่มีนามสกุล JPG หรือ PNG เท่านั้น');
            return;
        }
        const reader = new FileReader(); // สร้าง FileReader object เพื่ออ่านไฟล์
        reader.onload = function(e) {
            const imageSrc = e.target.result; // รับข้อมูลภาพในรูปแบบของ URL
            const Html = `
                <div class="card border-default" id="thumbCategory" style="width: 50%;">
                    <div class="form-group row p-3">
                        <div class="col-md-12">
                            <div class="thumbnail text-center" style="position: center;">
                                <img id="imgCategory" src="${imageSrc}" class="rounded" alt="ภาพสัญลักษณ์" style="width:40%">
                                <span class="btn btn-sm btn-danger rounded-pill px-3 py-0 delete-btn" style="position: absolute; bottom: -25px; right: 2px;" onclick="return deleteUpload()"><i class="far fa-trash-alt mr-1"></i> ลบ</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('#thumbCategoryContainer').html(Html);
            $('#thumbCategoryContainer').show();
        };
        if(file) {
            reader.readAsDataURL(file); // อ่านไฟล์แบบ Data URL
            $('#fileBG').hide();
        } else {
            alert('ไม่พบไฟล์');
        }
    });

    function deleteUpload() {
        const fileName = document.querySelector('#fileName');
        fileName.value = ''; // เคลียร์ค่าใน input file
        $('#thumbCategoryContainer').hide();
        $('#fileBG').show();
    }
</script>

<script>
    function getEdit(id) {
        
        $.ajax({
            url: 'edit_category/' + id,
            type: 'GET',
            success: function(data) {
                // นำข้อมูลที่ได้รับมาแสดงใน popup Edit
                $('#hdfIdUpdate').val(data.ID);
                $('#txtCategoryNameUpdate').val(data.CATEGORY_NAME);
                $('#txtCategoryDescUpdate').val(data.CATEGORY_DESC);
                
                if (data.FLAG_ACTIVE === "Y") {
                    $('#chkFlagActiveUpdate').prop('checked', true)
                    $('#txtchkFlagActiveUpdate').prop('textContent', 'เปิดการใช้งาน');
                } else {
                    $('#chkFlagActiveUpdate').prop('checked', false);
                    $('#txtchkFlagActiveUpdate').prop('textContent', 'ปิดการใช้งาน');
                }
                //หากมีรูปภาพให้แสดง 
                if(data.FILE_VALUE !== null && data.FILE_VALUE !== ''){
                    const Html = `
                        <div class="card border-default" id="thumbCategoryUpdate" style="width: 50%;">
                            <div class="form-group row p-3">
                                <div class="col-md-12">
                                    <div class="thumbnail text-center" style="position: center;">
                                        <img id="imgCategory" src="/storage/Category/${data.FILE_VALUE}" class="rounded" alt="ภาพสัญลักษณ์" style="width:40%">
                                        <span class="btn btn-sm btn-danger rounded-pill px-3 py-0 delete-btn" style="position: absolute; bottom: -25px; right: 2px;" onclick="return deleteUploadUpdate()"><i class="far fa-trash-alt mr-1"></i> ลบ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#thumbCategoryContainerUpdate').html(Html);
                    $('#thumbCategoryContainerUpdate').show();
                    $('#fileBGUpdate').hide();
                }else{
                    $('#thumbCategoryContainerUpdate').hide();
                    $('#fileBGUpdate').show();
                }
                $('#mdlEdit').modal('show'); // แสดง popup Edit
            }
        });
    }

    const fileNameUpdate = document.querySelector('#fileNameUpdate');  
    fileNameUpdate.addEventListener('change', function() {
        const file = this.files[0]; // เลือกไฟล์ที่ผู้ใช้เลือก
        // ตรวจสอบนามสกุลของไฟล์
        const fileNameParts = file.name.split('.');
        const fileExtension = fileNameParts[fileNameParts.length - 1].toLowerCase();
        if (fileExtension !== 'jpg' && fileExtension !== 'png') {
            // แจ้งเตือนถ้าไฟล์ไม่ใช่ JPG หรือ PNG
            alert('โปรดเลือกไฟล์รูปภาพที่มีนามสกุล JPG หรือ PNG เท่านั้น');
            return;
        }
        const reader = new FileReader(); // สร้าง FileReader object เพื่ออ่านไฟล์
        reader.onload = function(e) {
            const imageSrc = e.target.result; // รับข้อมูลภาพในรูปแบบของ URL
            const Html = `
                <div class="card border-default" id="thumbCategoryUpdate" style="width: 50%;">
                    <div class="form-group row p-3">
                        <div class="col-md-12">
                            <div class="thumbnail text-center" style="position: center;">
                                <img id="imgCategory" src="${imageSrc}" class="rounded" alt="ภาพสัญลักษณ์" style="width:40%">
                                <span class="btn btn-sm btn-danger rounded-pill px-3 py-0 delete-btn" style="position: absolute; bottom: -25px; right: 2px;" onclick="return deleteUploadUpdate()"><i class="far fa-trash-alt mr-1"></i> ลบ</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('#thumbCategoryContainerUpdate').html(Html);
        };
        if(file) {
            reader.readAsDataURL(file); // อ่านไฟล์แบบ Data URL
            $('#fileBGUpdate').hide();
            $('#thumbCategoryContainerUpdate').show();
        } else {
            alert('ไม่พบไฟล์');
        }
    });

    function deleteUploadUpdate() {
        const fileNameUpdate = document.querySelector('#fileNameUpdate');
        fileNameUpdate.value = ''; // เคลียร์ค่าใน input file
        $('#thumbCategoryContainerUpdate').hide();
        $('#fileBGUpdate').show();
        $('#thumbCategoryContainerUpdate').html('');
    }
</script>

<script>
    function submitUpdate(event) {

        event.preventDefault();// สั่งให้ event click หยุดทำงาน

        var txtCategoryNameUpdate = $('#txtCategoryNameUpdate').val();
        var txtCategoryDescUpdate = $('#txtCategoryDescUpdate').val();
        var isValid = true; // ตัวแปรสำหรับตรวจสอบสถานะการ validate

        if (txtCategoryNameUpdate.trim() === '') {
            $('#txtCategoryNameUpdate').addClass('is-invalid');
            $('#spnCategoryNameUpdate').html('กรุณาระบุชื่อหมวดหมู่ MOC');
            isValid = false;
        }else if (txtCategoryDescUpdate.trim().length > 100) {
            $('#txtCategoryNameUpdate').addClass('is-invalid');
            $('#spnCategoryNameUpdate').html('ชื่อหมวดหมู่ MOC มีความยาวเกิน 100 ตัวอักษร');
            isValid = false;
        }else{
            $('#txtCategoryNameUpdate').removeClass('is-invalid');
        }

        if (txtCategoryDescUpdate.trim() === '') {
            $('#txtCategoryDescUpdate').addClass('is-invalid');
            $('#spnCategoryDescUpdate').html('กรุณาระบุรายละเอียด');
            isValid = false;
        }else if (txtCategoryDescUpdate.trim().length > 255) {
            $('#txtCategoryDescUpdate').addClass('is-invalid');
            $('#spnCategoryDescUpdate').html('รายละเอียด มีความยาวเกิน 255 ตัวอักษร');
            isValid = false;
        }else{
            $('#txtCategoryDescUpdate').removeClass('is-invalid');
        }

        
        if (isValid) {
            confirmUpdate(); // ทำงานเมื่อ validate ผ่านทั้งหมด
        }else{
            return false;
        }
    }
</script>

<script type="text/javascript">
    function confirmUpdate() {
        
        Swal.fire({
            text: "คุณยืนยันที่จะบันทึกข้อมูล ใช่หรือไม่?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่ใช่'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('frmUpdate').submit(); // ถ้าผู้ใช้กด "ใช่" ให้ submit ฟอร์ม
            }
        });
        return false;
    }
</script>

<script>
    function submitCreate(event) {

        event.preventDefault();// สั่งให้ event click หยุดทำงาน

        var txtCategoryName = $('#txtCategoryName').val();
        var txtCategoryDesc = $('#txtCategoryDesc').val();
        var isValid = true; // ตัวแปรสำหรับตรวจสอบสถานะการ validate

        if (txtCategoryName.trim() === '') {
            $('#txtCategoryName').addClass('is-invalid');
            $('#spnCategoryName').html('กรุณาระบุชื่อหมวดหมู่ MOC');
            isValid = false;
        }else if (txtCategoryName.trim().length > 100) {
            $('#txtCategoryName').addClass('is-invalid');
            $('#spnCategoryName').html('ชื่อหมวดหมู่ MOC มีความยาวเกิน 100 ตัวอักษร');
            isValid = false;
        }else{
            $('#txtCategoryName').removeClass('is-invalid');
        }

        if (txtCategoryDesc.trim() === '') {
            $('#txtCategoryDesc').addClass('is-invalid');
            $('#spnCategoryDesc').html('กรุณาระบุรายละเอียด');
            isValid = false;
        }else if (txtCategoryDesc.trim().length > 255) {
            $('#txtCategoryDesc').addClass('is-invalid');
            $('#spnCategoryDesc').html('รายละเอียด มีความยาวเกิน 255 ตัวอักษร');
            isValid = false;
        }else{
            $('#txtCategoryDesc').removeClass('is-invalid');
        }
        
        if (isValid) {
            confirmCreate(); // ทำงานเมื่อ validate ผ่านทั้งหมด
        }else{
            return false;
        }

    }
</script>

<script type="text/javascript">
    function confirmCreate() {
        
        Swal.fire({
            text: "คุณยืนยันที่จะบันทึกข้อมูล ใช่หรือไม่?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่ใช่'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('frmCreate').submit(); // ถ้าผู้ใช้กด "ใช่" ให้ submit ฟอร์ม
            }
        });
        return false; // ไม่ให้บันทึกในรอบแรก
    }
</script>

<script type="text/javascript">
    function confirmDelete(Id,Name) {
        var urlToRedirect = "delete_category/" + Id;
        Swal.fire({
            text:"คุณยืนยันที่จะลบรายการนี้ ใช่หรือไม่?",
            icon:"question",
            showCancelButton: true,
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่ใช่'
            //confirmButtonColor: '#d33',
        }).then((result)=>{
            if(result.isConfirmed){
                window.location.href=urlToRedirect;
            }
        });
    }
</script>

@if(session('alert'))
    <script>
        let alertData = @json(session('alert'));

        Swal.fire({
            icon: alertData.icon,
            title: alertData.title,
            text: alertData.message,
        });
    </script>
@endif



@endsection
