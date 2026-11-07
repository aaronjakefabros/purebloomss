<?php

namespace Tests\Feature;

use App\Mail\Transport\GmailApiTransport;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mime\Email;
use Tests\TestCase;

class GmailApiTransportTest extends TestCase
{
    public function test_it_refreshes_the_access_token_and_sends_the_message_through_gmail_api(): void
    {
        $requests = [];
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use (&$requests): MockResponse {
            $requests[] = compact('method', 'url', 'options');

            if ($url === 'https://oauth2.googleapis.com/token') {
                return new MockResponse(
                    json_encode(['access_token' => 'test-access-token']),
                    ['http_code' => 200]
                );
            }

            return new MockResponse(
                json_encode(['id' => 'gmail-message-id']),
                ['http_code' => 200]
            );
        });

        $transport = new GmailApiTransport(
            'test-client-id',
            'test-client-secret',
            'test-refresh-token',
            $httpClient
        );
        $email = (new Email)
            ->from('sender@example.com')
            ->to('recipient@example.com')
            ->subject('Test OTP')
            ->text('Your verification code is 123456.');

        $sentMessage = $transport->send($email);

        $this->assertSame('gmail-message-id', $sentMessage->getMessageId());
        $this->assertCount(2, $requests);
        $this->assertSame('POST', $requests[0]['method']);
        $this->assertSame('https://oauth2.googleapis.com/token', $requests[0]['url']);
        parse_str($requests[0]['options']['body'], $tokenRequestBody);
        $this->assertSame('test-refresh-token', $tokenRequestBody['refresh_token']);
        $this->assertSame('POST', $requests[1]['method']);
        $this->assertSame(
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/send',
            $requests[1]['url']
        );
        $this->assertArrayHasKey('authorization', $requests[1]['options']['normalized_headers']);

        $messageRequestBody = json_decode($requests[1]['options']['body'], true);
        $rawMessage = base64_decode(strtr($messageRequestBody['raw'], '-_', '+/'));
        $this->assertStringContainsString('To: recipient@example.com', $rawMessage);
        $this->assertStringContainsString('Your verification code is 123456.', $rawMessage);
    }

    public function test_it_reports_gmail_oauth_errors_without_attempting_to_send(): void
    {
        $httpClient = new MockHttpClient([
            new MockResponse(
                json_encode([
                    'error' => 'invalid_grant',
                    'error_description' => 'The refresh token is invalid.',
                ]),
                ['http_code' => 400]
            ),
        ]);
        $transport = new GmailApiTransport(
            'test-client-id',
            'test-client-secret',
            'revoked-refresh-token',
            $httpClient
        );

        $this->expectException(TransportException::class);
        $this->expectExceptionMessage('Mail send failed (Gmail API): token request failed: The refresh token is invalid.');

        $transport->send(
            (new Email)
                ->from('sender@example.com')
                ->to('recipient@example.com')
                ->subject('Test OTP')
                ->text('Your verification code is 123456.')
        );
    }
}
