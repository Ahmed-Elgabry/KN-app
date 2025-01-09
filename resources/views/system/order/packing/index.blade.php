@extends('system.layout')

@section('content')

    <div class="table-responsive">
        <table class="table table-bordered" style="width: max-content;">
            <thead>

                <tr>
                    <td>{{ __('Reference ID') }}</td>
                    <td>{{ __('QR Code') }}</td>
                    <td>{{ __('Total Item Quantity') }}</td>
                    <td>{{ __('Scanned Quantity') }}</td>
                </tr>
            </thead>
            <tbody>
                <tr style="text-align: center">

                    <td>{{ $order->order->store_order_id }}</td>
                    <td> <img id='barcode'
                            src="https://api.qrserver.com/v1/create-qr-code/?data={{ $order->order->store_order_id }}&amp;size=100x100 "
                            width="50" height="50" /></td>
                    <td id="total_qty"></td>
                    <td id="scanned_qty">0</td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered" id="items-table">
            <thead>
                <tr>
                    <th width="100">{{__('ID')}}</th>
                    <th>{{__('Product name')}}</th>
                    <th width="180">{{__('SKU')}}</th>
                    <th>{{__('Bin Location')}}</th>
                    <th>{{__('Order Qty')}}</th>
                    <th>{{__('Picked Qty')}}</th>
                    <th>{{__('Status')}}</th>
                </tr>
            </thead>
            <tbody class="tableBody">
                @php
                    $tot_q = 0;
                @endphp
                @foreach ($order->detials as $fulfillment_item)
                    @php $tot_q+= $fulfillment_item->fulfilled_quantity;  @endphp
                    <tr>
                        <td width="120">
                            <strong>{{ $fulfillment_item->order_item->item_id }}</strong>

                        </td>

                        <td style="">
                            @if ($fulfillment_item->order_item->item->image != '')
                                <img width="70" src="{{ $fulfillment_item->order_item->item->image_url }}">
                            @endif
                            {{ $fulfillment_item->order_item->item->{'name_' . lang()} }}

                        </td>
                        <td class="sku">
                            @php
                                $barcodes = collect($fulfillment_item->order_item->item->barcodes)
                                    ->pluck('barcode')
                                    ->toArray();
                            @endphp
                            {{ implode(',', $barcodes) }}</td>
                        <td>{{ $fulfillment_item->order_item->item->sku }}</td>

                        <td class="@foreach ($barcodes as $barcode)t-{{ $barcode }} @endforeach">
                          <span class="badge badge-success">{{ $fulfillment_item->fulfilled_quantity }}</span></td>
                        <td class="@foreach ($barcodes as $barcode)b-{{ $barcode }} @endforeach">0</td>
                        <td><span class="badge badge-danger @foreach ($barcodes as $barcode)s-{{ $barcode }} @endforeach">Not packed</span> </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button id="Print_label" disabled="disabled" class="btn btn-secondary"> Print Label</button>

    </div>
@endsection
@section('footer')
    <script src="{{ asset('assets/js/jquery.scannerdetection.js') }}?sd={{ time() }}" type="text/javascript">
    </script>

    <script>
        $(document).ready(function() {
var  total_qty = {{$tot_q}};
var scanned_qty = 0;
$('#total_qty').html(total_qty);
            $(document).scannerDetection({
                timeBeforeScanTest: 200, // wait for the next character for upto 200ms
                endChar: [30], // be sure the scan is complete if key 13 (enter) is detected
                avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
                ignoreIfFocusOn: 'input', // turn off scanner detection if an input has focus
                onComplete: function(barcode, qty) {

                    if ($(`.b-${barcode}`).length) {
                        var old_value = $(`.b-${barcode}`).text();
                        var new_value = parseInt(old_value) + parseInt(qty);
                        var total_val = $(`.t-${barcode}`).text();

                        if(new_value > total_val){
                            Swal.fire({
                                title:'Item Reach Quantity',
                                icon: 'warning',
                                buttonsStyling: false,
                                showCloseButton: true,
                                showConfirmButton: false,

                            });
                        }

                        if (new_value <= total_val) {
                            $(`.b-${barcode}`).text(new_value);
                            $(`.s-${barcode}`).html(
                                `<bdi>${total_val-new_value}  more scans needed </bdi>`);
                                $(`.s-${barcode}`).removeClass('badge-danger').addClass('badge-warning');
                            scanned_qty  = +scanned_qty + 1;
                        }

                        if (new_value == total_val) {
                            $(`.t-${barcode}`).parent('tr').addClass('bg-light-success');
                            $(`.s-${barcode}`).text("Packed");
                            $(`.t-${barcode}`).parent('tr').appendTo('#items-table tbody');
                            $(`.s-${barcode}`).removeClass('badge-danger');
                            $(`.s-${barcode}`).removeClass('badge-warning').addClass('badge-success');

                        }

                        if(total_qty == scanned_qty){
                            $('#Print_label').removeAttr('disabled').removeClass('btn-secondary').addClass('btn-success');
                        }
                        $('#scanned_qty').html(scanned_qty );
                    } else {
                         Swal.fire({
                            title:'Incorrect Item',
                            icon: 'warning',
                            buttonsStyling: false,
                            showCloseButton: true,
                            showConfirmButton: false,

                        });
                    }
                }, // main callback function
                scanButtonKeyCode: 116, // the hardware scan button acts as key 116 (F5)
                scanButtonLongPressThreshold: 5, // assume a long press if 5 or more events come in sequence
                onScanButtonLongPressed: function(barcode,
                qty) {}, // callback for long pressing the scan button
                // onError: function(string){alert('Error ' + string);}
            });
        })
    </script>
@endsection
