<?php

namespace App\Providers;

use App\Mail\Transport\GmailApiTransport;
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
        Mail::extend('gmail_api', function (array $config) {
            foreach (['client_id', 'client_secret', 'refresh_token'] as $key) {
                if (! is_string($config[$key] ?? null) || trim($config[$key]) === '') {
                    throw new InvalidArgumentException('The Gmail API mailer requires GOOGLE_'.strtoupper($key).' to be configured.');
                }
            }

            return new GmailApiTransport(
                $config['client_id'],
                $config['client_secret'],
                $config['refresh_token'],
                HttpClient::create($config['client'] ?? [])
            );
        });

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
