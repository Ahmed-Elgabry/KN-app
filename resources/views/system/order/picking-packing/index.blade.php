@extends('system.layout')
@include('system.order.assign-picker',['route' => route('system.order.reassign_picker'),'title' => __('Re Assign Picker'),'button_text' => __('Re Assign')])

@include('system.order.picking-packing.picking-filter')
@section('content')
    @include('system.datatable')
@endsection








@section('footer')

    <script src="{{ asset('assets/js/jquery.scannerdetection.js') }}?sd={{ time() }}" type="text/javascript"></script>



    <script>
        $(document).ready(function() {

            $(document).scannerDetection({
                timeBeforeScanTest: 200, // wait for the next character for upto 200ms
                endChar: [30], // be sure the scan is complete if key 13 (enter) is detected
                avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
                ignoreIfFocusOn: 'input', // turn off scanner detection if an input has focus
                onComplete: function(barcode, qty) {
                    $.get('{{route("system.misc.ajax")}}?type=get_order_by_ref_id&store_order_id='+barcode,function (data){
                        if(data.status){
                            window.open(data.url, '_blank').focus();
                        }else{
                            notify(data.message, 'error');
                        }
                    })

                }, // main callback function
                scanButtonKeyCode: 116, // the hardware scan button acts as key 116 (F5)
                scanButtonLongPressThreshold: 5, // assume a long press if 5 or more events come in sequence

            });
        })
    </script>


<script>
    function submitForm(){
        $('#filterForm').submit();
        updateTotals();
    }
    $('#created_at1').on('apply.daterangepicker', function(ev, picker) {
        $('#filterForm').submit();
        updateTotals();
    });
    $('#created_at2').on('apply.daterangepicker', function(ev, picker) {
        $('#filterForm').submit();
        updateTotals();
    });
    var selectedValues = [];

    function getSelected(){
        selectedValues = [];
        $("[name='ids[]']").each(function(i,v){
            if($(v).is(':checked')){
                selectedValues.push(i)
                $('.order-inputs').append(`<input name="order_ids[]" value="${$(v).val()}"  type="hidden">`)
            }
        })
        $('.assign-btn').prop('disabled',false);

        $('.select-order-count').text(selectedValues.length);
    }
    function numberPerPicker(){
        var pickers = $('#picker_ids').val();
        var nu_pe  = selectedValues.length / pickers.length;
        if(pickers.length > selectedValues.length){
            var packer  = 'Number of selected picker bigger than selected orders';
            $('.error-div').html(`<div class="badge badge-danger">${packer}</div>`)
            $('.sub-btn').prop('disabled',true);
            return;
        }else{
            $('.error-div').html(``)
            $('.sub-btn').prop('disabled',false);
        }
        $('#order_per_picker').val(Math.ceil(nu_pe))
    }
    function resetFo(){
        $('#city_id').empty().trigger('change');
        $('#store_id').val('').change();
        $('#picker_id').val('').change();
        $('#region_id').val('').change();

    }

    function   updateTotals(){
        var formData = $('#filterForm').serialize();
        formData = formData+'&picking=' + 1
        $.get('{{route('system.order.picking-totals')}}',formData).done(function(response){
            console.log(response);
            $('.totals-div').html(response);
        })
    }

    var regions = @json($regions);
    function getCities(id){
        var region = regions.filter(function(obj) {
                return (obj.id == id);
            });
        if(region.length){
            $('#city_id').empty();
            $('#city_id').append(`<option value="all">${__('All')}</option>`);
            $.each(region[0].cities, function(key,val) {
                $('#city_id').append(`<option value="${val.id}">${$global_lang == 'ar' ? val.name_ar : val.name_en}</option>`)
            });
        }else{
            $('#city_id').empty()
        }
    }

    $(document).on('click',"[name='ids[]']",function(){
            if($(this).is(':checked')){
                $('.assign-btn').prop('disabled',false);

            }else{
                var total=$(document).find('[name="ids[]"]:checked').length;
                console.log(total);
                if(total == 0){
                    $('.assign-btn').prop('disabled',true);
                }
            }
    });
    $(document).on('click',"#checkall",function(){
        if($(this).is(':checked')){
            $('.assign-btn').prop('disabled',false);
        }else{
            $('.assign-btn').prop('disabled',true);
        }
    });
</script>
<script>
     function city_select(){
        var region_id  =$('#region_id').val();
        ajaxSelect2('#city_id', 'city',0,'',{'region_id':region_id});
    }
    $(document).ready(function(){
        ajaxSelect2('#city_id', 'city')
    })
</script>
@endsection
