<!-- Modal Search------------------------------------------------------------------------------------------------------------- -->
<div class="modal fade" id="mdlSearch" tabindex="-1" role="dialog" aria-labelledby="searchLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="min-width: 50%;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <strong class="modal-title">ค้นหาหมวดหมู่ MOC</strong>
                <button type="button" class="close button_close text-white" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('categories.index') }}">
            
            <div class="card-block container mt-3">
            
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="txtCategoryNameSearch" class="col-form-label text-md-right">{{ __('ชื่อหมวดหมู่ MOC') }}</label>
                        <input type="text" class="form-control" name="txtCategoryNameSearch" autofocus placeholder="โปรดระบุ" value="{{ request('txtCategoryNameSearch') }}"/>
                    </div>
                    <div class="col-md-6">
                        <label for="txtCategoryDescScearch" class="col-form-label text-md-right">{{ __('รายละเอียด') }}</label>
                        <input type="text" class="form-control" name="txtCategoryDescScearch" autofocus placeholder="โปรดระบุ" value="{{ request('txtCategoryDescScearch') }}"/>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="txtCreateDateSearch" class="col-form-label text-md-right">{{ __('วันที่สร้าง') }}</label>
                        <div class="inner-addon right-addon ">
                            <i class="fa fa-calendar"></i>
                            <input type="text" class="form-control datepicker pl-2" name="txtCreateDateSearch" autocomplete="off" value="{{ request('txtCreateDateSearch') }}"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="txtCreateMonthSearch" class="col-form-label text-md-right">{{ __('เดือนที่สร้าง') }}</label>
                        <div class="inner-addon right-addon ">
                            <i class="fa fa-calendar"></i>
                            <input type="text" class="form-control datepicker-month pl-2" name="txtCreateMonthSearch" autocomplete="off" value="{{ request('txtCreateMonthSearch') }}"/>
                        </div>
                    </div>
                </div>

            </div>                

            <div class="modal-footer n-border">
                <button type="button" class="btn btn-light" data-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary" name="BtnSubmitSearch">ค้นหา</button>
            </div>

            </form>

        </div>
    </div>
</div>