@section('sub-header-btns')
<!-- Button trigger modal -->

    <button type="button" class="btn btn-icon btn-primary" onclick="getSelected()" title="{{__('Reassign picker')}}" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="fa-solid fa-address-book p-0" style="font-size: 1.4rem"></i>
  </button>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> {{__('Assign picker')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {!! Form::open(['id'=>'main-form','onsubmit' => 'FormSubmit("'.route('system.order.reassign_picker') .'");return false;','class'=>'form','name'=>'assign-picker','method' =>  'POST']) !!}

        <div class="modal-body">
            <div class="error-div">

            </div>
            <div class="badge badge-warning">
                <span>{{__('Total Orders Selected')}}</span>
                <span class="select-order-count px-5"></span>
            </div>

            <div class="row">
                <div class="col-lg-12 mt-3">
                    @php
                        $all_picker= array_column($order_operators,'name','id');
                    @endphp
                                        {{ label(__('Pickers')) }}
                    <div class="mb-5">
                        {!! Form::select('picker_ids[]',$all_picker,null,['id'=>'picker_ids','class'=>'form-control form-control-solid select_ajax','onchange' => 'numberPerPicker()','data-control' => 'select2','data-placeholder' => __('Picker'),'multiple' => 'multiple']) !!}
                        <div class="invalid-feedback" id="picker_ids-form-error"></div>

                    </div>
                </div>
                <div class="order-inputs"></div>
                <div class="col-12">
                    {{ label(__('Number of Orders per Picker')) }}
                    <div class="mb-5">
                        {!! Form::number('order_per_picker',null,['id'=>'order_per_picker','class'=>'form-control form-control-solid']) !!}
                        <div class="invalid-feedback" id="order_per_picker-form-error"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
               <!--begin::Button-->
               <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
               <!--end::Button-->
               <!--begin::Button-->
               <button type="submit" class="btn btn-primary sub-btn">
                   <span class="indicator-label">{{  __('Assign')}}</span>
                   <span class="indicator-progress">{{__('Please wait')}}...
                           <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
               </button>
               <!--end::Button-->
        </div>
        {!! Form::close() !!}

      </div>
    </div>
  </div>
@endsection
