<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

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
        Mail::extend('brevo', function (array $config) {
            $apiKey = $config['key'] ?? null;

            if (! is_string($apiKey) || trim($apiKey) === '') {
                throw new InvalidArgumentException('The Brevo mailer requires BREVO_API_KEY to be configured.');
            }

            $factory = new BrevoTransportFactory(
                null,
                HttpClient::create($config['client'] ?? [])
            );

            return $factory->create(new Dsn('brevo+api', 'default', $apiKey));
        });
    }
}
