@extends('system.layout')
@include('system.order.assign-picker',
['route' => route('system.order.assign'),'title' => __('Assign picker'),'button_text' => __('Assign')])
@include('system.order.filter')
@section('content')
    @include('system.datatable')
@endsection
@section('footer')
<script>
    function submitForm(){
        $('#filterForm').submit();
        updateTotals();
    }
    $('#created_at1').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
        $('#filterForm').submit();

        updateTotals();

        setTimeout(() => {
            setToDate(picker.startDate.format('YYYY-MM-DD'))

        }, 300);
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
        $('#order_per_picker').text(Math.ceil(nu_pe))
        console.log(pickers.length);
    }

    function resetFo(){
        $('#city_id').empty().trigger('change');
        $('#order_received').val('').change();
        $('#order_type').val('').change();
        $('#shipping_type').val('').change();
        $('#store_id').val('').change();
        $('#picker_id').val('').change();
        $('#order_status_id').val('').change();
        $('#region_id').val('').change();

    }

    function updateTotals(){
        var formData = $('#filterForm').serialize();

        $.get('{{route('system.order.totals')}}',formData).done(function(response){
            $('.totals-div').html(response);
        })
    }

    var regions = @json($regions);
    function getCities(id){
        var region = regions.filter(function(obj) {
                return (obj.id == id);
            });
        if(region.length){
            $('#city_id').append(`<option value="all">${__('All')}</option>`);
            $.each(region[0].cities, function(key,val) {
                $('#city_id').append(`<option value="${val.id}">${$global_lang == 'ar' ? val.name_ar : val.name_en}</option>`)
            });
        }
    }

    function setToDate(value){
        $('#created_at2').daterangepicker({
            autoUpdateInput: false,
            todayHighlight: true,
            singleDatePicker: true,
            autoClose: true,
            autoApply: true,
            showDropdowns: true,
            minDate:new Date(value.replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3")),
            minYear: 2017,
            timePicker:false,
            maxYear: parseInt(moment().format('YYYY'), 11),
            maxDate: new Date(),
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            }
        });
        $('#created_at2').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('#created_at2').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
        $('#created_at2').on('apply.daterangepicker', function(ev, picker) {
        $('#filterForm').submit();
        updateTotals();
    });
    }
    $(document).on('click',"[name='ids[]']",function(){
            if($(this).is(':checked')){
                $('.assign-btn').prop('disabled',false);

            }else{
                var total=$(document).find('[name="ids[]"]:checked').length;
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
