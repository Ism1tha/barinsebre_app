<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function new(Request $request)
    {
        $existing_order = \App\Models\Order::where('date', $request->date)->where('turn', $request->turn)->first();
        if ($existing_order) {
            return response()->json(['id' => $existing_order->id]);
        }
        $order = new \App\Models\Order();
        $order->date = $request->date;
        $order->turn = $request->turn;
        $order->products = json_encode($request->products);
        $order->user_id = $request->user()->id;
        $order->save();
        return response()->json(['id' => $order->id]);
    }

    public function index(Request $request, $date, $turn)
    {
        //Get all orders by date, append user username and email from the foreign key
        $orders = \App\Models\Order::where('date', $date)->where('turn', $turn)->get();
        foreach ($orders as $order) {
            $order->user = \App\Models\User::find($order->user_id);
        }
        return response()->json($orders);
    }
}
