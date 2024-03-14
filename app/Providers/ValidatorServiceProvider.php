<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        \Validator::extend(
            'zip_code',
            function ($attribute, $value, $parameters, $validator) {
                return preg_match('/^[0-9]{3}-[0-9]{4}$/', $value);
            }
        );
        \Validator::extend(
            'phone_number',
            function ($attribute, $value, $parameters, $validator) {
                return preg_match('/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/', $value);
            }
        );
    }
}
