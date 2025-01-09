<div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
    <div class="card card-flush card-info flex-row-fluid">
        <div class="card-header">
            <div class="card-title">
                <h2>{{__('Order ID')}} (#{{$result->reference_id ?? ''}})</h2>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-bordered mb-0 fs-6 min-w-300px">
                    <tbody class="fw-semibold text-gray-600">
                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-calendar fs-2 me-2"></i>@lang('type')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->reference_type ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-receipt fs-2 me-2"></i>@lang('Coupon')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->coupon ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-truck fs-2 me-2"></i>
                                @lang('Courier')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->courier ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-truck fs-2 me-2"></i>
                                @lang('AWB')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->awb ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-arrow-down-up-across-line fs-2 me-2"></i>@lang('Order Status')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->order_status->name ?? ''}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-flush card-info flex-row-fluid">
        <div class="card-header">
            <div class="card-title">
                <h2>{{__('Customer')}}</h2>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-bordered mb-0 fs-6 min-w-300px">
                    <tbody class="fw-semibold text-gray-600">
                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-profile-circle fs-2 me-2"></i>@lang('Customer')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->customer_name ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-sms fs-2 me-2"></i>@lang('Email')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->customer_email ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-phone fs-2 me-2"></i>@lang('Phone')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->customer_phone ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="fa-regular fa-comment fs-2 me-2"></i>@lang('Comment')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->comment ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-profile-circle fs-2 me-2"></i>@lang('Shipping Name')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->shipping_name ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-phone fs-2 me-2"></i>@lang('Shipping Phone')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->shipping_phone ?? ''}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-flush card-info flex-row-fluid">
        <div class="card-header">
            <div class="card-title">
                <h2>@lang('Payment')</h2>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-bordered mb-0 fs-6 min-w-300px">
                    <tbody class="fw-semibold text-gray-600">
                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-money-check-dollar fs-2 me-2"></i>@lang('Payment Method')
                            </div>
                        </td>
                        <td class="fw-bold text-end"> {{ $result->payment_method ?? ''}} </td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-wallet fs-2 me-2"></i>@lang('Payment Fee')
                            </div>
                        </td>
                        <td class="fw-bold text-end"> {{ $result->payment_fee ?? ''}} </td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-wallet fs-2 me-2"></i>@lang('Payment Status')
                            </div>
                        </td>
                        <td class="fw-bold text-end">

                            {{ $result->payment_status }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-barcode fs-2 me-2"></i>@lang('Code Amount')
                            </div>
                        </td>
                        <td class="fw-bold text-end"> {{ $result->cod_amount ?? '' }} </td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-truck fs-2 me-2"></i>
                                @lang('Total Amount')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->total_amount ?? ''}}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-percent fs-2 me-2"></i>@lang('Exchange Rate')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{$result->exchange_rate ?? ''}}</td>
                    </tr>

                    <tr>
                        <td class="text-muted">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-monitor-mobile me-2"></i>@lang('Currency')
                            </div>
                        </td>
                        <td class="fw-bold text-end">{{ $result->currency ?? '' }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card card-flush card-info flex-row-fluid mt-3">
    <div class="card-header">
        <div class="card-title">
            <h2>@lang('address')</h2>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table align-middle table-row-bordered mb-0 fs-6 min-w-300px">
                <tbody class="fw-semibold text-gray-600">
                <tr>
                    <td class="text-muted">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-money-check-dollar fs-2 me-2"></i>@lang('Shipping Address')
                        </div>
                    </td>
                    <td class="fw-bold text-start">
                        {{ $result->shipping_country ? $result->shipping_country . ' - ' : '' }}
                        {{ $result->shipping_city ? $result->shipping_city . ' - ' : '' }}
                        {{ $result->shipping_full_address }}
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>


<!--begin::Orders-->
<div class="d-flex flex-column gap-7 gap-lg-10 pt-7">
    <!--begin::Product List-->
    <div class="card card-flush  flex-row-fluid overflow-hidden">
        <!--begin::Card header-->
        <div class="card-header">
            <div class="card-title">
                <h2>@lang('Order') #{{$result->reference_id}}</h2>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <!--begin::Table-->

                <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0 border-bottom">
                    <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">

                        <th class="min-w-100px">@lang('Product Id')</th>
                        <th class="min-w-100px">@lang('Netsuite Id')</th>
                        <th class="min-w-100px">@lang('Product Name')</th>
                        <th class="min-w-100px">@lang('Quantity')</th>
                        <th class="min-w-100px">@lang('Unit Price')</th>
                    </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                    @forelse($result->orderItem as $order)
                        <tr>
                            <td>{{$order->items->store_item_id }}</td>
                            <td>{{$order->items->netsuite_id }}</td>
                            <td>
                                <div class="d-flex align-items-center product-img">
                                    {{datatableImage($order->items->image_url)}}
                                    <div class="ms-5">
                                        <a href="#" target="_blank" class="fw-bold text-gray-600 pe-none">
                                            {{ (lang() == "ar") ?  $order->items->name_ar : $order->items->name_en}}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>{{$order->quantity}}</td>
                            <td>{{$order->unit_price}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{__('No Data')}}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
     </div>

</div>

