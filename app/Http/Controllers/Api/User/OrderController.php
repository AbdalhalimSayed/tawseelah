<?php

namespace App\Http\Controllers\Api\User;

use App\Helper\APIResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\OrderStoreRequest;
use App\Http\Resources\User\Order\OrderResorce;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller

{
    use APIResponse;

    public function index()
    {
        $orders = Auth::user()->orders()->latest()->get();

        return $this->success(OrderResorce::collection($orders), "All Orders For You");
    }

    public function show(Order $order)
    {
        if ($order->user_id == Auth::user()->id) {
            return $this->success(new OrderResorce($order));
        }
    }

    public function store(OrderStoreRequest $request)
    {
        $user = Auth::user();
        if (Order::where("user_id", $user->id)->exists()) {
            $order = Order::where("user_id", $user->id)->latest()->first();
            if ($order->status == "canceled" or $order->status == "delivered") {
                $data = $request->validated();
                $data["user_id"] = $user->id;
                Order::create($data);

                return $this->success(null, "Successfuly Order Create");
            } else {
                return $this->error("You Have Oreder Not Canceled");
            }
        } else {

            $data = $request->validated();
            $data["user_id"] = $user->id;
            Order::create($data);

            return $this->success(null, "Successfuly Order Create");
        }
    }

    public function cancel(Order $order)
    {
        if ($order->status === "canceled") {
            return $this->error("The Order Already Canceled!");
        } elseif ($order->status === "pending" or $order->status === "on_the_way" or $order->status === "accepted") {
            $order->status = "canceled";
            $order->save();
            return $this->success(new OrderResorce($order), "Success Canceled Order");
        } else {
            return $this->error("The Order Is Finished!");
        }
    }
}
