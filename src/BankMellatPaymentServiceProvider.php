<?php
/**
 * Created by PhpStorm.
 * User: Hasan Shafei [ www.netparadis.com ]
 * Date: 2/21/18
 * Time: 2:49 AM
 */

namespace shahrooz7216\MellatBank;

use Illuminate\Support\ServiceProvider;

/**
 * Class MellatPaymentServiceProvider
 * @author Hasan Shafei [ www.netparadis.com ] 

 */
class BankMellatPaymentServiceProvider extends ServiceProvider
{
    /**
     * Configuration bindings in the container.
     *
     * @return void
     * @see https://laravel.com/api/5.2/Illuminate/Support/ServiceProvider.html
     */
    public function configuration()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('BankMellatPayment.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/config.php', 'BankMellatPayment'
        );
    }

}