<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        //     VerifyEmail::createUrlUsing(function ($notifiable) {
    //         $frontendUrl = env('HOME_URL');
 
    //         $verifyUrl = URL::temporarySignedRoute(
    //             'verification.verify',
    //             Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
    //             [
    //                 'id' => $notifiable->getKey(),
    //                 'hash' => sha1($notifiable->getEmailForVerification()),
    //             ]
    //         );
 
    //         $parsed_url = parse_url($verifyUrl);
    //         $exploded = explode('/', $parsed_url['path']);
 
    //         return $frontendUrl . '/verification?id=' . $notifiable->getKey() . '&hash=' . end($exploded) . '&' . $parsed_url['query'];
    //     }

    }
}