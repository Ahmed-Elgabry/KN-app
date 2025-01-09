<?php

namespace App\Modules\System;

use App\Services\UserService;

use Illuminate\Http\Request;
use App;
use App\Services\ItemService;
use App\Services\OrderFulfillmentService;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;


class AjaxController extends SystemController{


    protected $item_service,
     $order_fulfillment_service,
     $order_service;

    public function __construct(
        ItemService $item_service,
        OrderFulfillmentService $order_fulfillment_service,
        OrderService $order_service
    )
    {
        parent::__construct();
        $this->item_service = $item_service;
        $this->order_fulfillment_service = $order_fulfillment_service;
        $this->order_service = $order_service;
    }

    public function index(Request $request){

        switch ($request->type) {


            case 'get_order_by_ref_id':

                $data = $this->order_fulfillment_service->getOrderByRefId($request->store_order_id);
                if(!$data) {
                    return ['status' => false, 'message' => __('Order Not Exist')];
                }
                return  ['status'=>true,'url'=>route('system.order-fulfillment.packing',$data->id)];
                break;

            case 'user':
                $word = $request->word;

                $data = App\Models\User::where('status', 1)
                    ->where(function ($query) use ($word) {
                        $query->where('firstname', 'LIKE', '%' . $word . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $word . '%')
                            ->orWhere('username', 'LIKE', '%' . $word . '%')
                            ->orWhere('mobile', 'LIKE', '%' . $word . '%');
                    });
                    if($request->module && $request->recordId){
                        $data = $data->doesntHave('filesShared', 'and',function($query) use($request) {
                            $query->where($request->module.'_id', $request->recordId);
                        });
                    }
                    $data = $data->get(['id as id',
                        \DB::raw('name as value')
                    ]);

                if(!$data) return [];
                return $data;
                case 'item':
                    $data = $this->item_service->search_item($request->word);
                    if (!$data)
                        return [];
                    return $data;
                    break;
                case 'city':
                    $data = $this->order_service->search_city($request);
                    if (!$data)
                        return [];
                    return $data;
                    break;

        }

    }

}
