<?php

namespace App\Services\Station;


use App\Filters\Id;
use App\Filters\Name;
use App\Filters\Status;
use App\Repositories\Station\StationRepository;
use App\Repositories\Warehouse\WarehouseRepository;
use App\Services\BaseService;
use Datatables;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class StationService extends BaseService
{
    protected $stationRepository,$warehouseRepository;

    public function __construct(StationRepository $stationRepository,WarehouseRepository $warehouseRepository)
    {
        parent::__construct();
        $this->stationRepository = $stationRepository;
        $this->warehouseRepository = $warehouseRepository;
    }


    public function loadViewData(): array
    {
        $this->pageTitle('Station List');
        $this->breadcrumb('Configuration', 'system.station.index');
        $this->tableColumns([
            __('ID'),
            __('Name'),
            __('Max Order Count'),
            __('Status'),
            __('Created Date'),
            __('Action'),
        ]);

        $this->jsColumns([
            'id' => 'station.id',
            'name' => 'station.name',
            'max_order_count' => 'station.max_order_count',
            'status' => 'station.status',
            'added_at' => 'station.added_at',
            'action' => '',
        ]);

        $this->showAdvancedFilter(true);
        $this->addModalIcon('system.station.store', 'station-modal');
        $this->otherData([
            'warehouse' => array_column($this->warehouseRepository->getDataTableQuery()->get()->toArray(),'name','id'),
        ]);
        return $this->retunData;
    }


    public function loadDataTableData()
    {
        $query = $this->stationRepository->getDataTableQuery();
        $eloquentData = app(Pipeline::class)
            ->send($query)
            ->through([
                Id::class,
                Name::class,
                Status::class,
            ])->thenReturn();
        return Datatables::eloquent($eloquentData)
            ->addColumn('id', '{{$id}}')
            ->addColumn('status', function ($data) {
                return status_icon($data->status);
            })
            ->addColumn('max_order_count', function ($data) {
                return $data->max_order_count;
            })
            ->addColumn('added_at', '{{$added_at}}')
            ->editColumn('action', function ($data) {
                $this->actionButtons(link_modal_edit('system.station.edit', route('system.station.edit', $data->id), 'station-modal', $data->id, null, 'id', route('system.station.update', $data->id)));
                return $this->actionButtonsRender($this->stationRepository->modelPath(), $data->id);
            })
            ->escapeColumns([])
            ->setRowId(function ($data) {
                return 'tr_' . $data->id;
            })
            ->make(true);
    }

    public function create()
    {
        $this->pageTitle('Create Station');
        $this->breadcrumb('stations', 'system.station.index');

        $this->otherData([
            'warehouse' => array_column($this->warehouseRepository->getDataTableQuery()->get()->toArray(),'name','id'),
        ]);

        return $this->retunData;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'max_order_count' => $request->max_order_count,
                'warehouse_id' => $request->warehouse_id,
                'added_at' => \Carbon::now(),
            ];
            $store = $this->stationRepository->store($data);
            DB::commit();
            return $store;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->stationRepository->find($id);
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'max_order_count' => $request->max_order_count,
                'status' => $request->status,
            ];
            $this->stationRepository->update($data, $id);

            DB::commit();
            return [
                'data' => $data,
                'status' => status_icon($request->status)->render(),
                'id' => $id];
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function stationOverview()
    {
        $this->pageTitle('Stations Overview');
        $this->breadcrumb('Configuration');


        $stations = $this->stationRepository->getStations();

        $stations = $stations->map(function ($station) {
            $station->progress = ($station->ready_orders / $station->max_order_count) * 100;

            if ($station->progress >= 0 && $station->progress <= 75) {
                $station->BarColor = '#88b6e9';
            } elseif ($station->progress > 75 && $station->progress <= 99) {
                $station->BarColor = '#a9e2c5';
            } else {
                $station->BarColor = '#e785a2';
            }

            return $station;
        });


        $this->otherData(['stations' => $stations]);
        return $this->retunData;
    }

}
