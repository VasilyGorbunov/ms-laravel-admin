<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $order = Order::paginate();
        return OrderResource::collection($order);
    }

    public function show($id)
    {
        return new OrderResource(Order::find($id));
    }

    public function export()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=orders.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() {

            $orders = Order::all();
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Product Title', 'Price', 'Quantity'
            ]);

            // Body
            foreach($orders as $order) {
                fputcsv($file, [
                    $order->id, 
                    $order->name, 
                    $order->email, 
                    '', '', ''
                ]);

                foreach ($order->orderItems as $orderItem) {
                    fputcsv($file, [
                        '', '', '', 
                        $orderItem->product_title,
                        $orderItem->price,
                        $orderItem->quantity
                    ]);
                }
            }

            fclose($file);

        };

        return \Response::stream($callback, 200, $headers);
    }
}
