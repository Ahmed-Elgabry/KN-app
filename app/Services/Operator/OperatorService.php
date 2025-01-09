<?php

namespace App\Services\Operator;


use App\Filters\Id;
use App\Filters\Name;
use App\Filters\OperatorType;
use App\Filters\Status;
use App\Repositories\Operator\OperatorRepository;
use App\Repositories\Operator\OperatorTypeRepository;
use App\Services\BaseService;
use Carbon\Carbon;
use Datatables;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class OperatorService extends BaseService
{
    protected $operatorRepository;

    public function __construct(OperatorRepository $operatorRepository)
    {
        parent::__construct();
        $this->operatorRepository = $operatorRepository;
    }


    public function loadViewData(): array
    {
        $this->pageTitle('Operator List');
        $this->breadcrumb('Operator', 'system.operator.index');
        $this->tableColumns([
            __('ID'),
            __('Name'),
            __('Telephone'),
            __('Created At'),
            __('Action'),
        ]);

        $this->jsColumns([
            'id' => 'operators.id',
            'name' => 'operators.name',
            'phone' => '',
            'created_at' => '',
            'action' => '',
        ]);

        $this->addButton('system.operator.create');
        $this->showAdvancedFilter(true);
        return $this->retunData;
    }


    public function loadDataTableData()
    {
        $query = $this->operatorRepository->getDataTableQuery();
        $eloquentData = app(Pipeline::class)
            ->send($query)
            ->through([
                Id::class,
                Name::class,
            ])->thenReturn();
        return Datatables::eloquent($eloquentData)
            ->addColumn('id', '{{$id}}')
            ->addColumn('name', function ($data) {
                return $data->name;
            })
            ->addColumn('phone', '{{$phone}}')
            ->addColumn('created_at', '{{$created_at}}')
            ->editColumn('action', function ($data) {
                $this->actionButtons(datatable_menu_edit(route('system.operator.edit', $data->id), 'system.operator.edit'));
                $this->actionButtons(datatable_menu_delete(route('system.operator.destroy', $data->id) ,'system.operator.destroy','tr_'.$data->id));
                return $this->actionButtonsRender($this->operatorRepository->modelPath(), $data->id);
            })
            ->escapeColumns([])
            ->setRowId(function ($data) {
                return 'tr_' . $data->id;
            })
            ->make(true);
    }

    public function create(): array
    {
        $this->pageTitle('Create Operator');
        $this->breadcrumb('Operators', 'system.operator.index');
        return $this->retunData;
    }

    public function store($request)
    {
         DB::beginTransaction();
        try {
            $store = $this->operatorRepository->store([
                'name' => $request->name,
                'phone' => $request->phone,
                'created_at' => Carbon::now(),
            ]);
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
        $this->pageTitle('Edit Operator');

        $this->breadcrumb('Operators', 'system.operator.index');

        $this->otherData([
            'result' =>  $this->operatorRepository->find($id),
        ]);

        return $this->retunData;
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
            ];
            $this->operatorRepository->update($data, $id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        return  $this->operatorRepository->destroy($id);
    }




}
