
@push('filter')
<style>
    .parent{
        position: relative;
    }
    .filter-btn-div{
        position: absolute;
        z-index: 9;
        @if(lang() == 'en')
        right: -10px;

        @else
        left: -10px;

        @endif
    }
    .filter-btn{
        border-radius: 50%;
        width: max-content;
        padding: 6px 11px !important;
    }
    th {
    white-space: nowrap;
}
</style>
{!! Form::open(['id'=>'filterForm','onsubmit'=>'filterFunction("'.$datatableURL.'","'.$datatableVar.'",$(this));return false;']) !!}
    <!--begin::Card-->
    <div class="d-flex align-items-center col-12 p-0 parent">
        <!--begin::Input group-->
     
        <!--end::Input group-->
        <!--begin:Action-->
        <div class="d-flex align-items-center mb-2 filter-btn-div">
            <a id="kt_horizontal_search_advanced_link " class="btn btn-primary filter-btn" data-bs-toggle="collapse" href="#kt_advanced_search_form" aria-expanded="false"><i class="fas fa-filter p-0"></i></a>
        </div>
        <!--end:Action-->
    </div>
    <div class="card mb-7">
        <!--begin::Card body-->
        <div class="card-body d-flex flex-wrap p-0">
            <!--begin::Compact form-->

            <!--end::Compact form-->
            <!--begin::Advance form-->
            <div class="collapse show col-12 mt-9 p-5" id="kt_advanced_search_form" style="">
                <!--begin::Separator-->
                <!--end::Separator-->
                <!--begin::Row-->
                <div class="row g-8">
                    <div class="row gx-10">

                        <div class="col-lg-2">
                            <div class="mb-5">
                                {!! Form::text('reference_id',null,['class'=>'form-control form-control-solid',
                                'id'=>'reference_id','autocomplete'=>'off','placeholder' => __('Reference ID'),'oninput' => "submitForm()"]) !!}
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mb-5">
                                @php
                                $all_store= [''=>'',__('All stores')]+array_column($stores,'name','id');
                            @endphp
                                {!! Form::select('ful_store_id',$all_store,null,['id'=>'store_id','class'=>'form-control form-control-solid select_ajax','data-control' => 'select2','data-placeholder' => __('Store'),'onchange' => "submitForm()",'data-hide-search'=>"true"]) !!}
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mb-5">
                                @php
                                    $all_region= ['' => '']+array_column($regions,'name','id');
                                @endphp
                                {!! Form::select('ful_region_id',$all_region,null,['id'=>'region_id','class'=>'form-control form-control-solid select_ajax','data-control' => 'select2','data-placeholder' => __('Region'),'onchange' => "submitForm();city_select()",'data-hide-search'=>"false",'data-allow-clear'=>"true"]) !!}
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mb-5">
                              
                                {!! Form::select('ful_city_id[]',[],null,['id'=>'city_id','class'=>'form-control form-control-solid select_ajax','data-placeholder' => __('City'),'onchange' => "submitForm()",'multiple' => 'multiple']) !!}
                            </div>
                        </div>
                      
                        <div class="col-lg-2">
                            @php
                                $all_picker= [__('All pickers')]+array_column($order_operators,'name','id');
                            @endphp
                            <div class="mb-5">
                                {!! Form::select('picker_ids[]',$all_picker,null,['id'=>'picker_id','class'=>'form-control form-control-solid select_ajax','data-control' => 'select2','data-placeholder' => __('Picker'),'onchange' => "submitForm()",'data-hide-search'=>"true",'multiple' => 'multiple']) !!}
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <button type="reset" class="btn btn-warning"  onclick="resetFo()">{{__('Reset')}}</button>
                        </div>
                    </div>
                </div>
               
                <!--end::Row-->
            
            </div>
            <!--end::Advance form-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
{!! Form::close() !!}
<div class="totals-div">

@include('system.order.picking-packing.totals-section')
</div>
@endpush
