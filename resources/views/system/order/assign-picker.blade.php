@section('sub-header-btns')
<!-- Button trigger modal -->

    <button type="button" disabled class="btn  btn-primary assign-btn" onclick="getSelected()" title="{{$title}}" data-bs-toggle="modal" data-bs-target="#exampleModal">
      <i class="fa-solid fa-award"style="font-size: 1.4rem"></i>   {{$title}}
  </button>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> {{$title}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {!! Form::open(['id'=>'main-form','onsubmit' => 'FormSubmit("'.$route.'");return false;','class'=>'form','name'=>'assign-picker','method' =>  'POST']) !!}

        <div class="modal-body">
            <div class="error-div" id="form-alert-message">

            </div>
            <div class="badge badge-warning">
                <span>{{__('Total Orders Selected')}}</span>
                <span class="select-order-count px-5"></span>
            </div>

            <span class='badge badge-success'>
                <span>{{__('Number of Orders per Picker')}}</span>

                <span id='order_per_picker' class="px-5"> 0</span>
            </span>

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

            </div>
        </div>
        <div class="modal-footer">
               <!--begin::Button-->
               <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
               <!--end::Button-->
               <!--begin::Button-->
               <button type="submit" class="btn btn-primary sub-btn">
                   <span class="indicator-label">{{ $button_text}}</span>
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
