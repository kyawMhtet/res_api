<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    //
    public function index()
    {
        try {
            $orders = Order::with('dish')->get();

            return response()->json([
                'orders' => $orders,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error Orders');
        }
    }

    // change status
    public function changeStatus(Request $request)
    {
        $order = Order::where('id', $request->oid);

        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'status changed'
        ], 200);
    }
}
