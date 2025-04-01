<?php

use App\Http\Controllers\BaseController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class NotificationsController extends BaseController
{
    static public function sendNotificationTo($user, $alert): bool
    {
        // Validate inputs

        // Prepare the payload
        $data = [
            'app_id' => env('ONE_SIGNAL_APP_ID'),
            'headings' => ['en' => $alert['title']],
            'contents' => ['en' => $alert['content']],
            'target_channel' => 'push',
            'include_aliases' => [
                'external_id' => 'carServices-' . $user->id
            ]
        ];

        // Initialize Guzzle client
        $client = new Client([
            'base_uri' => 'https://api.onesignal.com/',
            'timeout' => 0, // No timeout (adjust as needed)
        ]);

        try {
            $response = $client->post('notifications', [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Authorization' => 'Basic ' . env('ONE_SIGNAL_AUTHORIZE'),
                ],
                'json' => $data, // Automatically encodes to JSON
            ]);

            // Check response
            $result = json_decode($response->getBody()->getContents(), true);
            if ($response->getStatusCode() >= 400 || isset($result['errors'])) {
                report(new \Exception('OneSignal Error: ' . json_encode($result['errors'] ?? 'Unknown')));
                return false;
            }

            return true;
        } catch (RequestException $e) {
            // Handle Guzzle-specific exceptions (e.g., network errors, 4xx/5xx responses)
            if ($e->hasResponse()) {
                $error = json_decode($e->getResponse()->getBody()->getContents(), true);
                report(new \Exception('OneSignal Request Failed: ' . json_encode($error['errors'] ?? $e->getMessage())));
            } else {
                report($e);
            }
            return false;
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            report($e);
            return false;
        }
    }
}
