<?php

namespace App\Services;


use App\Filters\CreatedAtFrom;
use App\Filters\CreatedAtTo;
use App\Filters\Id;
use App\Filters\Name;
use App\Filters\OperatorType;
use App\Filters\Status;
use App\Models\BillItems;
use App\Models\Operator;
use App\Models\Type;
use App\Repositories\BillRepository;
use App\Repositories\Operator\OperatorRepository;
use App\Repositories\Operator\OperatorTypeRepository;
use App\Repositories\TypeRepository;
use App\Services\BaseService;
use Carbon\Carbon;
use Datatables;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class BillService extends BaseService
{
    protected $operatorRepository;

    public function __construct(BillRepository $operatorRepository)
    {
        parent::__construct();
        $this->operatorRepository = $operatorRepository;
    }


    public function loadViewData(): array
    {
        $this->pageTitle('Bills');
        $this->tableColumns([
            __('ID'),
            __('Operator'),
            __('Total'),
            __('Created At'),
            __('Action'),
        ]);

        $this->jsColumns([
            'id' => 'bill.id',
            'operator' => 'bill.operator_id',
            'total' => '',
            'created_at' => 'bill.created_at',
            'action' => '',
        ]);

        $this->addButton('system.bill.create');
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
                CreatedAtFrom::class,
                CreatedAtTo::class
            ])->thenReturn();
        return Datatables::eloquent($eloquentData)
            ->addColumn('id', '{{$id}}')
            ->addColumn('operator', function ($data) {
                return $data->operator->name;
            })
            ->addColumn('total', function ($data) {
                $total = 0;
                foreach ($data->billItems as $items){
                    $total+= $items->type_price;
                }
                return$total;
            })
            ->addColumn('created_at', '{{$created_at}}')
            ->editColumn('action', function ($data) {
                $this->actionButtons(datatable_menu_edit(route('system.bill.edit', $data->id), 'system.bill.edit'));
                $this->actionButtons(datatable_menu_delete(route('system.bill.destroy', $data->id) ,'system.bill.destroy','tr_'.$data->id));
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
                'operator_id' => $request->operator_id,
            ]);

            foreach ($request->type as $type){
                $typeData = Type::where('id', $type)->first();
                BillItems::create([
                    'bill_id' => $store->id,
                    'type_id' => $typeData->id,
                    'type_name' => $typeData->name,
                    'type_price' => $typeData->price,
                ]);
            }

            DB::commit();
           return true;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function create(): array
    {
        $this->pageTitle('Create Bill');
        $this->breadcrumb('Bills', 'system.bill.index');
        $operators = Operator::all();
        $types = Type::all();
        $this->otherData([
            'operators' =>  $operators,
            'types' =>  $types,
        ]);
        return $this->retunData;
    }

    public function edit($id)
    {
        $this->pageTitle('Edit Bill');

        $this->breadcrumb('Bills', 'system.bill.index');

        $operators = Operator::all();
        $types = Type::all();
        $this->otherData([
            'operators' =>  $operators,
            'types' =>  $types,
            'result' =>  $this->operatorRepository->find($id),
        ]);

        return $this->retunData;
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            BillItems::where('bill_id', $id)->delete();
            $bill = $this->operatorRepository->find($id);
            $bill->update([
                'operator_id' => $request->operator_id,
                'updated_at' => now()
            ]);
            foreach ($request->type as $type){
                $typeData = Type::where('id', $type)->first();
                BillItems::create([
                    'bill_id' => $id,
                    'type_id' => $typeData->id,
                    'type_name' => $typeData->name,
                    'type_price' => $typeData->price,
                ]);
            }
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
