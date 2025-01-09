<?php

namespace App\Services;


use App\Filters\Id;
use App\Filters\Name;
use App\Filters\OperatorType;
use App\Filters\Status;
use App\Repositories\Operator\OperatorRepository;
use App\Repositories\Operator\OperatorTypeRepository;
use App\Repositories\TypeRepository;
use App\Services\BaseService;
use Carbon\Carbon;
use Datatables;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class TypeService extends BaseService
{
    protected $operatorRepository;

    public function __construct(TypeRepository $operatorRepository)
    {
        parent::__construct();
        $this->operatorRepository = $operatorRepository;
    }


    public function loadViewData(): array
    {
        $this->pageTitle('Type List');
        $this->breadcrumb('Types', 'system.type.index');
        $this->tableColumns([
            __('ID'),
            __('Name'),
            __('Price'),
            __('Created At'),
            __('Action'),
        ]);

        $this->jsColumns([
            'id' => 'types.id',
            'name' => 'types.name',
            'Price' => '',
            'created_at' => '',
            'action' => '',
        ]);

        $this->addButton('system.type.create');
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
            ->addColumn('Price', '{{$price}}')
            ->addColumn('created_at', '{{$created_at}}')
            ->editColumn('action', function ($data) {
                $this->actionButtons(datatable_menu_edit(route('system.type.edit', $data->id), 'system.type.edit'));
                $this->actionButtons(datatable_menu_delete(route('system.type.destroy', $data->id) ,'system.type.destroy','tr_'.$data->id));
                return $this->actionButtonsRender($this->operatorRepository->modelPath(), $data->id);
            })
            ->escapeColumns([])
            ->setRowId(function ($data) {
                return 'tr_' . $data->id;
            })
            ->make(true);
    }

    public function store($request)
    {
         DB::beginTransaction();
        try {
            $store = $this->operatorRepository->store([
                'name' => $request->name,
                'price' => $request->price,
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

    public function create(): array
    {
        $this->pageTitle('Create Type');
        $this->breadcrumb('Types', 'system.type.index');
        return $this->retunData;
    }

    public function edit($id)
    {
        $this->pageTitle('Edit Type');

        $this->breadcrumb('Types', 'system.type.index');

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
                'price' => $request->price,
            ];
            $this->operatorRepository->update($data, $id);
            DB::commit();
            return  true;
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
