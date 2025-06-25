<!-- Modal Edit------------------------------------------------------------------------------------------------------------- -->
<div class="modal fade" id="mdlEdit" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="min-width: 50%;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <strong class="modal-title">แก้ไข MOC Category</strong>
                <button type="button" class="close button_close text-white" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            
            <form id="frmUpdate" method="POST" action="{{ route('categories.update') }}" enctype="multipart/form-data">
                @csrf <!-- {{ csrf_field() }} -->
                <div class="card-block container mt-3">
                    <input id="hdfIdUpdate" type="hidden" name="hdfIdUpdate" />

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="txtCategoryNameUpdate" class="col-form-label text-md-right">{{ __('ชื่อ MOC Category') }}</label>
                            <input id="txtCategoryNameUpdate" type="text" class="form-control" name="txtCategoryNameUpdate" autofocus placeholder="โปรดระบุ" />
                            <span id="spnCategoryNameUpdate" class="invalid-feedback" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="txtCategoryDescUpdate" class="col-form-label text-md-right">{{ __('รายละเอียด') }}</label>
                            <textarea id="txtCategoryDescUpdate" name="txtCategoryDescUpdate" class="form-control" rows="3" cols="50" autofocus placeholder="โปรดระบุ" ></textarea>
                            <span id="spnCategoryDescUpdate" class="invalid-feedback" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label  class="col-form-label text-md-right">{{ __('ภาพสัญลักษณ์') }}</label>
                            <input id="fileNameUpdate" name="fileNameUpdate" type="file" class="visually-hidden">
                            <div id="fileBGUpdate" class="position-relative">
                                <div class="d-flex align-items-center justify-content-center w-100">
                                    <label for="fileNameUpdate" class="d-flex flex-column rounded-lg border border-4 border-dashed w-100 h-60 p-10 text-center" style="border-style: dashed !important;border-: 10px;">
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
                        </div>
                    </div>
                    
                    <div id="thumbCategoryContainerUpdate">
                        <div class="card border-default" id="thumbCategoryUpdate" style="width: 50%;">
                            <div class="form-group row p-3">
                                <div class="col-md-12">
                                    <div class="thumbnail text-center" style="position: center;">
                                        <img id="imgCategory" src="" class="rounded" alt="ภาพสัญลักษณ์" style="width:40%">
                                        <span class="btn btn-sm btn-danger rounded-pill px-3 py-0 delete-btn" style="position: absolute; bottom: -25px; right: 2px;" onclick="return deleteUploadUpdate()"><i class="far fa-trash-alt mr-1"></i> ลบ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label  class="col-form-label text-md-right">{{ __('สถานะการใช้งาน') }}</label>
                            <div class="custom-control custom-switch" style="padding-left: 0;">
                                <label class="switch">
                                    <input type="checkbox" id="chkFlagActiveUpdate" name="chkFlagActiveUpdate"/>
                                    <span class="slider icon round"><span class="text-slider" id="txtchkFlagActiveUpdate"></span></span>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>                

                <div class="modal-footer n-border">
                    <button type="button" class="btn btn-outline-dark rounded-pill btn-sm px-3" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill btn-sm px-3" name="BtnSubmitUpdate" onclick="return submitUpdate(event)">บันทึก</button>
                </div>

            </form>

        </div>
    </div>
</div>