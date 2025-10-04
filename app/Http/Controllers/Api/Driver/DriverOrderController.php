<?php

namespace App\Http\Controllers\Api\Driver;

use App\Helper\APIResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\Order\UpdateOrderStatusRequest;
use App\Http\Resources\User\Order\OrderResorce;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverOrderController extends Controller
{
    use APIResponse;
    public function __construct()
    {
        $this->middleware("auth:driver");
    }

    public function index()
    {
        $driver = Auth::guard('driver')->user();
        $orders = $driver->orders()->latest()->get();
        return $orders;
    }
    public function pending()
    {
        $ordersPending = Order::where("status", "pending")->latest()->get();
        return $this->success(OrderResorce::collection($ordersPending), "All Orders Pending");
    }

    public function accept(Order $order)
    {
        $driver = Auth::guard('driver')->user();
        if($driver->orders()->where("status", "accepted")->orwhere("status", "on_the_way")->exists()){
            return $this->error("You Have Already Order First Delivered Order Then Take Other Order");
        }
        if ($order->status == "pending") {

            $order->driver_id = $driver->id;
            $order->status = "accepted";
            $order->save();

            return $this->success(new OrderResorce($order), "Success Accept Order");
        }
//        if ($order->driver_id == $driver->id) {
//            return $this->success(new OrderResorce($order), "Alerady You Have This Order");
//        }
        return $this->error("Order Is Canceled Or Accepted From Other Driver");
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $driver = Auth::guard('driver')->user();
        if ($order->driver_id == $driver->id and $order->status != "delivered" and $order->status != "canceled") {
            $order->status = $request->status;
            $order->save();
            return $this->success(new OrderResorce($order), "Success Update Order");
        }
        return  $this->error("Order Is Canceled Or Updated From Other Driver");
    }
}
