<!DOCTYPE html>
<html dir="{{ lang() == 'en' ? 'ltr'  :'rtl' }}" lang="{{ lang() }}">
  <head>
    <meta charset="UTF-8" />
    <link href="/assets/css/style.bundle{{direction()}}.css?v=1.1" rel="stylesheet" type="text/css"/>
    <link href="/assets/css/custom.css?v={{time()}}" rel="stylesheet"/>
    <script src="/assets/js/scripts.bundle.js?v=1.0"></script>

    @if(lang() =='ar')
        <link href="assets/css/custom-ar.css?v={{time()}}" rel="stylesheet"/>
    @endif
    <style type="text/css">
      .textBold { font-weight: bold; }
      .main { page-break-after: always; }
      .textLeft { float: left; }
      .textRight { float: right; }
      .betweenBarcode { margin-left: 50px; font-size: 24px; }
      .orderDates { margin-top: 70px; margin-bottom: 10px; }
      .orderData { float: left; font-size: 16px; }
      .orderNumber { float: right; font-size: 40px; position: relative; top: -45px; left: -15px; }
      .tableBody { font-size : 17px; font-wight: 800; }
      .orderOption { height: 25px; width: 25px; border-radius: 50%; display: inline-block; }
      .sku { font-weight: bold; text-align: center; }
      .customerGroup {position: relative; top: -45px; left: 370px; font-size: 40px; }
      .orderPaid {position: relative; top: -45px; left: 370px; font-size: 40px; margin-left: 20px;}
      .table-bordered{
        border: 1px solid #000;
      }
      .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 5px;
      }
      .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
        border: 1px solid #000;
      }
    </style>
  </head>
<body>
<div class="container-fluid">

  @foreach ($orders as $order)
    <div class="main" style="margin-top: 5px">

      <table class="table table-bordered">
        <thead>
        <tr style="text-align: center; font-weight: bold; font-size:16px"><td colspan="8">{{$order->shipping_country == 'SA' ?'Domestic':'International' }}</td></tr>

        <tr>
          <td>{{__('Barcode') }}</td>
          <td>{{__('Reference ID') }}</td>
          <td>{{__('Order date')}}</td>
          <td>{{__('Print date') }}</td>
           <td>{{__('Picker') }}</td>
          <td>{{__('QR Code') }}</td>
          <td>{{__('City') }}</td>
        </tr>
        </thead>
        <tbody>
        <tr style="text-align: center">

            <td>  <img  src="https://barcodeapi.org/api/128/{{$order->store_order_id}}"  width="100" height="50" /></td>
            <td>{{$order->store_order_id }}</td>
          <td>{{$order->order_date ? date('d/m/Y H:m',strtotime($order->order_date)) : '' }}</td>
          <td>{{date('d/m/Y H:m') }}</td>
           <td>{{$order->op_name }}</td>
          <td>  <img id='barcode' src="https://api.qrserver.com/v1/create-qr-code/?data={{$order->id}}&amp;size=100x100 "  width="50" height="50" /></td>
          <td>{{$order->city->{'name_'.lang()}  ?? $order->shipping_city}}</td>
        </tr>
        </tbody>
      </table>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="100">ID</th>
            <th>Image</th>
            <th>Name</th>
            <th width="180">SKU</th>
            <th width="180">Bin Location</th>
            <th>Qty</th>
          </tr>
        </thead>
        <tbody class="tableBody">
          @php
           $tot_q = 0;
           @endphp

           @foreach ($order->order_fulfillment_detials as $product)
            <tr>
              <td width="120">
               <strong>{{ $product->order_item->item->id }}</strong>

              </td>

              <td style="padding: 0">
                @if ($product['image'] != '' )
                  <img width="70" src="{{ $product->order_item->item->image_url }}" >
                @endif
              </td>
              <td style="font-size: 12px">
                {{ $product->order_item->item->{'name_'.lang()} }}
              </td>
                <td class="sku">
                    @php
                        $barcodes = collect($product->order_item->item->barcodes)
                            ->pluck('barcode')
                            ->toArray();
                    @endphp
                    {{ implode(',', $barcodes) }}</td>
                <td class="sku"> {{ $product->order_item->item->sku }}</td>
              <td > <strong>{{ $product->fulfilled_quantity }}</strong></td>
            </tr>
          @php
           $tot_q += $product->fulfilled_quantity;
           @endphp
            @endforeach
          <tr ><td colspan="5">Total Items </td>   <td>{{ $tot_q }}</td></tr>
        </tbody>
      </table>

    </div>
  @endforeach
</div>
<script>
    window.print()
</script>
</body>
</html>
