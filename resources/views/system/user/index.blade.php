@extends('system.layout')

@section('content')
    @include('system.datatable')
@section('filter')

    <!--begin::Modal-->
    <div class="modal fade" id="filter-modal"  aria-hidden="true" role="dialog">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
            {!! Form::open(['id'=>'filterForm','onsubmit'=>'filterFunction("'.$datatableURL.'","'.$datatableVar.'",$(this));return false;']) !!}
            <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bolder">{{__('Filter')}}</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body  ">

                    <div class="row gx-10">
                        <div class="col-lg-6">
                            {{label('ID')}}
                            <div class="mb-5">
                                {!! Form::number('id',null,['class'=>'form-control form-control-solid']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row gx-10">
                        <div class="col-lg-6">
                            {{label('Date Start')}}
                            <div class="mb-5">
                                {!! Form::text('created_at_from',null,['class'=>'form-control form-control-solid dp','id'=>'created_at1','autocomplete'=>'off']) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            {{label('Date End')}}
                            <div class="mb-5">
                                {!! Form::text('created_at_to',null,['class'=>'form-control form-control-solid dp','id'=>'created_at2','autocomplete'=>'off']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="row gx-10">
                        <div class="col-lg-6">
                            {{label('Name')}}
                            <div class="mb-5">
                                {!! Form::text('name',null,['class'=>'form-control form-control-solid']) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            {{label('E-mail')}}
                            <div class="mb-5">
                                {!! Form::email('email',null,['class'=>'form-control form-control-solid']) !!}
                            </div>
                        </div>

                    </div>

                    <div class="row gx-10">
                        <div class="col-lg-6">
                            {{label('Status')}}
                            <div class="mb-5">
                                {!! Form::select('status',[''=>__('All'),1=>__('Active'),'-1'=>__('in-active')],null,['id'=>'select2_custom','class'=>'form-control form-control-solid']) !!}
                                <div name="select2_custom_search"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            {{label('Permission Group')}}
                            <div class="mb-5">
                                {!! Form::select('permission_group_id',[__('All')]+$PermissionGroup,null,['id' =>'permission_group','class'=>'form-control form-control-solid']) !!}
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">{{__('Filter')}}</span>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
            {!! Form::close() !!}
            <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection
@endsection

