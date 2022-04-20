<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(128);
        Relation::morphMap([
            'cars'         => 'App\Models\Car',
            'motor_cycles' => 'App\Models\MotorCycle'
        ]);
    }
}
