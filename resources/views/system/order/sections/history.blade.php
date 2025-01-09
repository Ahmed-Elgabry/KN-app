<div class="d-flex flex-column gap-7 gap-lg-10">
    <div class="card card-flush   flex-row-fluid">
        <div class="card-header align-items-center">
            <div class="card-title">
                <h2>@lang('Order History')</h2>
            </div>
        </div>


        <div class="card-body pt-0">

            <div class="tab-content" id="myTabContent">
                <div class="table-responsive">
                    {{view('system.datatable',$orderHistoryData)}}
                </div>
            </div>
        </div>
    </div>
</div>
