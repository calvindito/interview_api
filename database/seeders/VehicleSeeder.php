<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Stock;
use App\Models\Vehicle;
use App\Models\MotorCycle;
use Faker\Factory as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $type  = ['cars', 'motor_cycles'];

        for($i = 1; $i <= 50; $i++) {
            $rand_type = Arr::random($type);

            if($rand_type == 'cars') {
                $create = Car::create([
                    'machine'            => 'Mesin ' . rand(100, 900),
                    'passenger_capacity' => rand(2, 8),
                    'type'               => 'Tipe ' . rand(1000, 9999)
                ]);
            } else {
                $create = MotorCycle::create([
                    'machine'      => 'Mesin ' . rand(100, 900),
                    'suspension'   => 'Suspensi ' . rand(2, 8),
                    'transmission' => 'Transmisi ' . rand(1, 8)
                ]);
            }

            for($a = 1; $a <= rand(2, 5); $a++) {
                $vehicle = Vehicle::create([
                    'vehicleable_type' => $rand_type,
                    'vehicleable_id'   => $create->id,
                    'release'          => rand(1998, date('Y')),
                    'price'            => $rand_type == 'car' ? rand(100000000, 500000000) : rand(14000000, 40000000),
                    'color'            => $faker->colorName
                ]);
            }

            for($b = 1; $b <= rand(10, 20); $b++) {
                Stock::create([
                    'vehicle_id' => $vehicle->id,
                    'frame'      => strtoupper(Str::random(10))
                ]);
            }
        }
    }
}
