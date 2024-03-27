<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //

        view()->share('context', [
            'title' => 'Laravel Application',
            'content' => 'This is a Laravel application.',
            'icp' => 'ICP: 12345678',
            'js' => "console.log('Hello, Laravel! in php.');",
        ]);

        view()->share('friendLinks', [
            [
                'name' => 'Google',
                'url' => 'https://www.google.com',
            ],
            [
                'name' => 'Bing',
                'url' => 'https://www.bing.com',
            ],
            [
                'name' => 'Yahoo',
                'url' => 'https://www.yahoo.com',
            ],
        ]);

    }
}
