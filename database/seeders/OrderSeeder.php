<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Stock;
use App\Models\OrderDetail;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = ['cars', 'motor_cycles'];
        for($i = 1; $i <= 10; $i++) {
            $rand_type = Arr::random($type);
            if($rand_type == 'cars') {
                $car_stock = Stock::whereHas('vehicle', function($query) {
                        $query->where('vehicleable_type', 'cars');
                    })
                    ->whereDoesnthave('orderDetail', function($query) {
                        $query->whereColumn('order_details.stock_id', 'stocks.id');
                    })
                    ->limit(rand(1, 3))
                    ->get();

                $total = 0;
                $order = Order::create(['number' => rand(1000000000, 9999999999), 'total' => 0]);

                foreach($car_stock as $cs) {
                    $price  = $cs->vehicle->price;
                    $total += $price;

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'stock_id' => $cs->id,
                        'price'    => $price
                    ]);
                }

                Order::find($order->id)->update(['total' => $total]);
            } else {
                $motor_cycle_stock = Stock::whereHas('vehicle', function($query) {
                        $query->where('vehicleable_type', 'motor_cycles');
                    })
                    ->whereDoesnthave('orderDetail', function($query) {
                        $query->whereColumn('order_details.stock_id', 'stocks.id');
                    })
                    ->limit(rand(1, 3))
                    ->get();

                $total = 0;
                $order = Order::create(['number' => rand(1000000000, 9999999999), 'total' => 0]);

                foreach($motor_cycle_stock as $mcs) {
                    $price  = $mcs->vehicle->price;
                    $total += $price;

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'stock_id' => $mcs->id,
                        'price'    => $price
                    ]);
                }

                Order::find($order->id)->update(['total' => $total]);
            }
        }
    }
}
