<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Order;
use App\Models\MotorCycle;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EndPointController extends Controller {

    public function stockVehicle(Request $request)
    {
        $response = [];
        $per_page = $request->has('per_page') ? $request->per_page : 10;

        $validation = Validator::make($request->all(), [
            'type' => ['required', Rule::in(['cars', 'motor_cycles'])]
        ], [
            'type.required' => 'Field type tidak boleh kosong',
            'type.in'       => 'Mohon menulis type cars / motor_cycles'
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 422);
        } else {
            if($request->type == 'cars') {
                $data = Car::where(function($query) use ($request) {
                        if($request->machine) {
                            $query->where('machine', $request->machine);
                        }
                    })
                    ->paginate($per_page);
            } else if($request->type == 'motor_cycles') {
                $data = MotorCycle::where(function($query) use ($request) {
                        if($request->machine) {
                            $query->where('machine', 'like', "%$request->machine%");
                        }
                    })
                    ->paginate($per_page);
            } else {
                return 'Invalid type';
            }

            foreach($data as $d) {
                $vehicle = [];
                foreach($d->vehicle as $v) {
                    $sold = 0;
                    foreach($v->stock as $s) {
                        $sold += $s->orderDetail ? 1 : 0;
                    }

                    $stock     = $v->stock->count();
                    $available = $stock - $sold < 0 ? 0 : $stock - $sold;

                    $vehicle[] = [
                        'color'     => $v->color,
                        'price'     => $v->price,
                        'stock'     => $stock,
                        'sold'      => $sold,
                        'available' => $available
                    ];
                }

                if($request->type == 'cars') {
                    $response[] = [
                        'machine'            => $d->machine,
                        'passenger_capacity' => $d->passenger_capacity,
                        'type'               => $d->type,
                        'vehicle'            => $vehicle
                    ];
                } else {
                    $response[] = [
                        'machine'      => $d->machine,
                        'suspension'   => $d->suspension,
                        'transmission' => $d->transmission,
                        'vehicle'      => $vehicle
                    ];
                }
            }

            return response()->json([
                'page'       => $data->currentPage(),
                'per_page'   => $per_page,
                'total_data' => $data->total(),
                'total_page' => ceil($data->total() / $per_page),
                'result'     => $response
            ], 200);
        }
    }

    public function sale(Request $request)
    {
        $response = [];
        $per_page = $request->has('per_page') ? $request->per_page : 10;

        $validation = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m'
        ], [
            'date.required'    => 'Field date tidak boleh kosong',
            'date.date_format' => 'Contoh format ' . date('Y-m')
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 422);
        } else {
            $date = explode('-', $request->date);
            $data = Order::whereYear('created_at', $date[0])
                ->whereMonth('created_at', $date[1])
                ->where(function($query) use ($request) {
                    if($request->number) {
                        $query->where('number', $request->number);
                    }
                })
                ->paginate($per_page);

            foreach($data as $d) {
                $vehicle = [];
                foreach($d->orderDetail as $od) {
                    $vehicle[] = [
                        'machine' => $od->stock->vehicle->vehicleable->machine,
                        'frame'   => $od->stock->frame,
                        'price'   => $od->price,
                        'color'   => $od->stock->vehicle->color,
                        'type'    => $od->stock->vehicle->vehicleable_type
                    ];
                }

                $response[] = [
                    'number'  => $d->number,
                    'total'   => $d->total,
                    'date'    => $d->created_at->format('Y-m'),
                    'vehicle' => $vehicle
                ];
            }

            return response()->json([
                'page'       => $data->currentPage(),
                'per_page'   => $per_page,
                'total_data' => $data->total(),
                'total_page' => ceil($data->total() / $per_page),
                'result'     => $response
            ], 200);
        }
    }

    public function saleVehicle(Request $request)
    {
        $response = [];
        $per_page = $request->has('per_page') ? $request->per_page : 10;

        $validation = Validator::make($request->all(), [
            'date' => 'date_format:Y-m',
            'type' => [Rule::in(['cars', 'motor_cycles'])]
        ], [
            'date.date_format' => 'Contoh format ' . date('Y-m'),
            'type.in'          => 'Mohon menulis type cars / motor_cycles'
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 422);
        } else {
            $data = OrderDetail::where(function($query) use ($request) {
                    if($request->date) {
                        $date = explode('-', $request->date);
                        $query->whereYear('created_at', $date[0])
                            ->whereMonth('created_at', $date[1]);
                    }

                    if($request->type) {
                        $query->whereHas('stock', function($query) use ($request) {
                                $query->whereHas('vehicle', function($query) use ($request) {
                                        $query->where('vehicleable_type', $request->type);
                                    });
                            });
                    }

                    if($request->order) {
                        $query->whereHas('order', function($query) use ($request) {
                                $query->where('number', $request->order);
                            });
                    }

                    if($request->frame) {
                        $query->whereHas('stock', function($query) use ($request) {
                                $query->where('frame', $request->frame);
                            });
                    }

                    if($request->machine) {
                        $query->whereHas('stock', function($query) use ($request) {
                                $query->whereHas('vehicle', function($query) use ($request) {
                                        $query->whereHas('vehicleable', function($query) use ($request) {
                                                $query->where('machine', $request->machine);
                                            });
                                    });
                            });
                    }
                })
                ->paginate($per_page);

            foreach($data as $d) {
                $response[] = [
                    'order'   => $d->order->number,
                    'date'    => $d->created_at->format('Y-m'),
                    'machine' => $d->stock->vehicle->vehicleable->machine,
                    'frame'   => $d->stock->frame,
                    'price'   => $d->price,
                    'color'   => $d->stock->vehicle->color,
                    'release' => $d->stock->vehicle->release,
                    'type'    => $d->stock->vehicle->vehicleable_type
                ];
            }

            return response()->json([
                'page'       => $data->currentPage(),
                'per_page'   => $per_page,
                'total_data' => $data->total(),
                'total_page' => ceil($data->total() / $per_page),
                'result'     => $response
            ], 200);
        }
    }

}
