<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GmailApiTransport extends AbstractTransport
{
    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $refreshToken,
        private readonly HttpClientInterface $httpClient,
    ) {
        parent::__construct();
    }

    public function __toString(): string
    {
        return 'gmail-api';
    }

    protected function doSend(SentMessage $message): void
    {
        $accessToken = $this->getAccessToken();
        $response = $this->httpClient->request(
            'POST',
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/send',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$accessToken,
                ],
                'json' => [
                    'raw' => rtrim(strtr(base64_encode($message->toString()), '+/', '-_'), '='),
                ],
            ]
        );

        $statusCode = $response->getStatusCode();
        $payload = $response->toArray(false);

        if ($statusCode < 200 || $statusCode >= 300) {
            $error = $payload['error']['message'] ?? 'Unknown Gmail API error.';

            throw new TransportException("Mail send failed (Gmail API): {$error} (HTTP {$statusCode}).");
        }

        if (! isset($payload['id']) || ! is_string($payload['id'])) {
            throw new TransportException('Mail send failed (Gmail API): the response did not include a message ID.');
        }

        $message->setMessageId($payload['id']);
    }

    private function getAccessToken(): string
    {
        $response = $this->httpClient->request(
            'POST',
            'https://oauth2.googleapis.com/token',
            [
                'body' => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'refresh_token' => $this->refreshToken,
                    'grant_type' => 'refresh_token',
                ],
            ]
        );

        $statusCode = $response->getStatusCode();
        $payload = $response->toArray(false);

        if (
            $statusCode < 200
            || $statusCode >= 300
            || ! isset($payload['access_token'])
            || ! is_string($payload['access_token'])
        ) {
            $error = $payload['error_description'] ?? $payload['error'] ?? 'Unable to obtain an access token.';

            throw new TransportException("Mail send failed (Gmail API): token request failed: {$error} (HTTP {$statusCode}).");
        }

        return $payload['access_token'];
    }
}
