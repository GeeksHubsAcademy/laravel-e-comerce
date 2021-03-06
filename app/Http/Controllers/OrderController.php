<?php

namespace App\Http\Controllers;

use App\Mail\OrderShipped;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function getAll()
    {
       try {
           $orders = Order::with(['products.categories','user'])->get();
        //    Order.findAll({
        //      include:[
        //        {
        //            model:Product，
        //            include:[Category]
        //        },
        //        User
        //     ]
        //     })
           return response($orders);
       } catch (\Exception $e) {
          return response($e,500);
       }
    }
    public function insert(Request $request)
    {
        try {
            $body = $request->validate([
                'deliveryDate' => 'required|date',
                'products' => 'required|array'
            ]);
            $body['status'] = 'pending';
            $body['user_id'] = Auth::id();
            $products=$body['products'];
            unset($body['products']); //agregamos unset para eliminar la propiedad/elemento del objeto/array , eliminamos el elemento products para no tener error al crear el order
            $order = Order::create($body);
            //order=Order.create(req.body)
            $order->products()->attach($products);
            //order.addProduct(req.body.products)
            $order = $order->load('user','products');
            Mail::to($order->user->email)->send(new OrderShipped($order));
            return response($order, 201);
            //el formato que debe tener el array de products es el siguiente
            // "products":{
            //     "2":{
            //         "units":5
            //     },
            //     "7":{
            //         "units":20
            //     }
            // }
            // esto lo entiende php como 
            // "products"=>[
            //     20=>['units'=>5],
            //     7=>['units'=>20]
            // ]
        } catch (\Exception $e) {
            return response($e, 500);
        }
    }
}
