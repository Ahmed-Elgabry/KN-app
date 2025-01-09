{!! Form::button('<i class="fas fa-download"></i>', [
    'id' => 'ExportReporttoExcel',
    'class' => '  btn btn-icon btn-info  ',
    'onclick' => "filterFunction($('#filterForm'),true)",
    'href' => 'javascript:;',
    'title' => __('Download Excel'),
]) !!}
