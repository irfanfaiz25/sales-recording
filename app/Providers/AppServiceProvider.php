<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

use App\Models\Payment;
use App\Observers\PaymentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Payment::observe(PaymentObserver::class);

        // Create custom blade directive for formatting currency in Rupiah
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo number_format($expression, 2, ',', '.'); ?>";
        });
    }
}
