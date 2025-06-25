<!-- Modal Create----------------------------------------------------------------------------------------------------------- -->
<div class="modal fade" id="mdlCreate" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="min-width: 50%;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <strong class="modal-title">เพิ่ม MOC Category</strong>
                <button type="button" class="close button_close text-white" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            
            <form id="frmCreate"  method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
                @csrf <!-- {{ csrf_field() }} -->
                <div class="card-block container mt-3">
                    
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="txtCategoryName" class="col-form-label text-md-right">{{ __('ชื่อ MOC Category') }}</label>
                            <input id="txtCategoryName" type="text" class="form-control" name="txtCategoryName" autofocus placeholder="โปรดระบุ" />
                            <span id="spnCategoryName" class="invalid-feedback" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="txtCategoryDesc" class="col-form-label text-md-right">{{ __('รายละเอียด') }}</label>
                            <textarea id="txtCategoryDesc" name="txtCategoryDesc" class="form-control" rows="3" cols="50" placeholder="โปรดระบุ" ></textarea>
                            <span id="spnCategoryDesc" class="invalid-feedback" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-form-label text-md-right">{{ __('ภาพสัญลักษณ์') }}</label>
                            <input id="fileName" name="fileName" type="file" class="visually-hidden">
                            <div id="fileBG" class="position-relative">
                                <div class="d-flex align-items-center justify-content-center w-100">
                                    <label for="fileName" class="d-flex flex-column rounded-lg border border-4 border-dashed w-100 h-60 p-10 text-center" style="border-style: dashed !important;border-: 10px;">
                                        <div class="h-100 w-100 text-center d-flex flex-column align-items-center justify-content-center pt-5 pb-5">
                                            <div class="d-flex flex-auto max-height-48 w-2/5 mx-auto mt-n10">
                                                <img class="h-36 object-center" src="/storage/Assets/icons/upload_file.png" alt="">
                                            </div>
                                            <p class="text-muted mt-3" style="font-size: x-small;"><span style="font-size: small;color: black;font-weight: 500;">ลากและวางไฟล์ที่นี้ หรือ<span class="text-primary" style="text-decoration: underline;">เลือกไฟล์</span> เพื่ออัปโหลด</span><br /><br /> **ประเภทไฟล์ที่รองรับ: JPG,PNG ขนาดไฟล์: 1 ไฟล์/30 MB</p>
                                        </div>
                                        <span id="spnFileNameUpdate" class="invalid-feedback" role="alert"></span>
                                    </label>
                                </div>
                            </div>
                            <span id="spnFileName" class="invalid-feedback" role="alert"></span>
                        </div>
                    </div>

                    <div id="thumbCategoryContainer">
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label  class="col-form-label text-md-right">{{ __('สถานะการใช้งาน') }}</label>
                            <div class="custom-control custom-switch" style="padding-left: 0;">
                                <label class="switch">
                                    <input type="checkbox" id="chkFlagActive" name="chkFlagActive" checked/>
                                    <span class="slider icon round"><span class="text-slider" id="txtchkFlagActive">เปิดการใช้งาน</span></span>
                                </label>
                            </div>
                            
                        </div>
                    </div>

                </div>

                <div class="modal-footer n-border">
                    <button type="button" class="btn btn-outline-dark rounded-pill btn-sm px-3" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill btn-sm px-3" name="btnSubmitCreate" onclick="return submitCreate(event)">เพิ่ม</button>
                </div>

            </form>

        </div>
    </div>
</div>