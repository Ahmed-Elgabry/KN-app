@extends('system.layout')

@section('content')

    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                       href="#kt_ecommerce_sales_order_summary">{{ __('Summary') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                       href="#kt_ecommerce_sales_order_history">{{ __('History') }}</a>
                </li>


            </ul>

        </div>


        <div class="tab-content">

            <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
                @include('system.order.sections.summery')
            </div>

            <div class="tab-pane fade" id="kt_ecommerce_sales_order_history" role="tab-panel">
                @include('system.order.sections.history')
            </div>

        </div>
    </div>

@endsection

