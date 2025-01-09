<?php
namespace App\Modules\System;


use App\Http\Requests\OperatorFormRequest;
use App\Services\Operator\OperatorService;
use App\Services\TypeService;
use Illuminate\Http\Request;

class TypeController extends SystemController
{
    protected $operatorService;

    public function __construct(TypeService $operatorService)
    {
        parent::__construct();
        $this->operatorService = $operatorService;
    }


    public function index(Request $request)
    {
        if ($request->isDataTable) {
            return $this->operatorService->loadDataTableData();
        }
        return $this->view('type.index', $this->operatorService->loadViewData());
    }

    public function create(Request $request)
    {
        return $this->view('type.create', $this->operatorService->create());
    }

    public function store(Request $request)
    {
        $store = $this->operatorService->store($request);
        if ($store) {
            flash_msg('success',__('Data added successfully'));
            return $this->success( __( 'Data added successfully' ),
                [ 'url' => route( 'system.type.index' )] );
        }
        return $this->fail(__( 'Sorry, we could not add the data' ) );
    }
    public function edit($id)
    {
        return $this->view('type.create', $this->operatorService->edit($id));
    }

    public function update(Request $request,$id)
    {
        $update = $this->operatorService->update($request, $id);
        if ($update) {
            flash_msg('success',__( 'Data Updated successfully' ));
            return $this->success( __( 'Data Updated successfully' ),
                ['url' => route( 'system.type.index' )]);
        } else {
            return $this->fail(__( 'Sorry, we could not Update the data' ) );
        }
    }

    public function destroy($id ,Request $request)
    {
        $deleted = $this->operatorService->delete($id);
        if ($deleted) {
            flash_msg('success',__( 'operator Deleted successfully' ));
            return $this->success(__('operator Deleted'),
                ['url' => route('system.type.index')]);
        }
        return $this->fail(__( 'Sorry, we could not Update the data' ) );

    }



}
