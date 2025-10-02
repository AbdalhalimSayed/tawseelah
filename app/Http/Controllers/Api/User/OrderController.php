<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\OrderStoreRequest;
use Illuminate\Http\Request;

class OrderController extends Controller



    public function store(OrderStoreRequest $request)
    {
        return response()->json("Store Order", 200);
    }
}
