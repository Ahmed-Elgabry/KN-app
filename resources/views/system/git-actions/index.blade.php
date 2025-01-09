@extends('system.layout')
@section('links')

@endsection
@section('content')

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Branch Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($branches as $branch)
                
                <tr>
                    <td>{{$branch->name}}</td>
                    <td>  
                        <span class="label label-inline label-light-primary font-weight-bold">
                            @php
                                $form_id = 'main-form-'.$branch->name;
                            @endphp
                            {!! Form::open(['id'=>$form_id,'onsubmit' => 'FormSubmit("'.route('system.checkout-branch',['branch_name' => $branch->name]).'","'.$form_id.'");return false;','method' => 'POST']) !!}
                            <div id="form-alert-message"></div>

                            <div class="d-grid mb-10" >
                                <button type="submit" class="btn w-50 git-btn {{$branch->name == $current_branch ? 'btn-success disabled' : 'btn-primary' }}">
                                    <span class="indicator-label">{{__('Checkout')}}</span>
                                    <span class="indicator-progress">{{__('Please wait ...')}}
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            {!! Form::close() !!}
                        </span>
                    </td>
                </tr>
            @endforeach
   
        </tbody>
    </table>
@endsection
