@extends('system.layout')

@section('content')
    @include('system.datatable')


    <div id="update_modal"></div>

    @section('filter')
        <div class="modal fade" id="filter-modal" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    {!! Form::open(['id'=>'filterForm','onsubmit'=>'filterFunction("'.$datatableURL.'","'.$datatableVar.'",$(this));return false;']) !!}
                    <div class="modal-header">
                        <h2 class="fw-bolder">{{__('Filter')}}</h2>

                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>

                    <div class="modal-body">

                        <div class="row gx-10">
                            <div class="col-lg-6">
                                {{label('ID')}}
                                <div class="mb-5">
                                    {!! Form::number('id',null,['class'=>'form-control form-control-solid']) !!}
                                </div>
                            </div>

                            <div class="col-lg-6">
                                {{label('Name')}}
                                <div class="mb-5">
                                    {!! Form::text('name',null,['class'=>'form-control form-control-solid']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row gx-10">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="reset" class="btn btn-warning" onclick="resetFo()">{{__('Reset')}}</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">{{__('Filter')}}</span>
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    @endsection
@endsection

