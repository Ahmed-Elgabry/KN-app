@extends('system.layout')

@section('content')


    {!! Form::open(['id'=>'main-form','onsubmit' =>  isset($result) ? 'FormSubmit("'.route('system.type.update',$result->id).'");return false;':'FormSubmit("'.route('system.type.store') .'");return false;','method' => isset($result) ?  'PATCH' : 'POST']) !!}
    <div id="form-alert-message"></div>
    <!--begin::Row-->
    <div class="row gx-10 ">
        <!--begin::Col-->
        <div class="col-lg-6 ">
        {{ label(__('Name'),'required') }}
        <!--begin::Input group-->
            <div class="mb-5">
                {!! Form::text('name',isset($result->name) ? $result->name:old('name'),['class'=>'form-control form-control-solid', 'required' => 'required']) !!}
                <div class="invalid-feedback" id="name-form-error"></div>
            </div>

            <!--end::Input group-->
        </div>

        <div class="col-lg-6 ">
            {{ label(__('Price'),'required') }}
            <!--begin::Input group-->
            <div class="mb-5">
                {!! Form::text('price',isset($result->price) ? $result->price:old('phone'),['class'=>'form-control form-control-solid', 'required' => 'required']) !!}
                <div class="invalid-feedback" id="price-form-error"></div>
            </div>
            <!--end::Input group-->
        </div>

    </div>
    <!--end::Row-->
    <div class="separator separator-dashed mb-8"></div>

    <button type="submit" class="btn btn-primary submit">
        <span class="indicator-label">{{ isset($result->id)? __('Update') :  __('Create')}}</span>
        <span class="indicator-progress">{{__('Please wait')}}...
						<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
    </button>

    {!! Form::close() !!}

@endsection

