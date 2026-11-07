<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_the_account_only_after_email_otp_verification(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'phone' => '09123456789',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertRedirect(route('otp.verify'));

        $this->assertDatabaseCount('users', 0);

        $otp = null;
        Mail::assertSent(OtpMail::class, function (OtpMail $mail) use (&$otp): bool {
            $otp = (string) $mail->otp;

            return $mail->hasTo('customer@example.com');
        });

        $this->assertNotNull($otp);

        $this->post('/verify-otp', ['otp' => $otp])
            ->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'email' => 'customer@example.com',
            'phone' => '09123456789',
            'is_verified' => true,
            'wallet_balance' => 200.00,
        ]);
        $this->assertAuthenticatedAs(User::where('email', 'customer@example.com')->firstOrFail());
    }

    public function test_invalid_otp_does_not_create_an_account(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertRedirect(route('otp.verify'));

        $this->from('/verify-otp')
            ->post('/verify-otp', ['otp' => '000000'])
            ->assertRedirect('/verify-otp')
            ->assertSessionHas('error', 'Invalid OTP. Please try again.');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_resending_otp_sends_a_new_code(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertRedirect(route('otp.verify'));

        $this->from('/verify-otp')->post('/resend-otp')->assertRedirect('/verify-otp');

        $sentMails = Mail::sent(OtpMail::class);
        $this->assertCount(2, $sentMails);
        $this->assertSame(
            hash_hmac('sha256', (string) $sentMails[1]->otp, config('app.key')),
            session('pending_registration.otp_hash')
        );

        $this->post('/verify-otp', ['otp' => (string) $sentMails[1]->otp])
            ->assertRedirect(route('home'));
        $this->assertDatabaseHas('users', ['email' => 'customer@example.com']);
    }

    public function test_brevo_mail_transport_is_registered(): void
    {
        $transport = app('mail.manager')->createSymfonyTransport([
            'transport' => 'brevo',
            'key' => 'test-api-key',
        ]);

        $this->assertSame('brevo+api://api.brevo.com', (string) $transport);
    }
}
