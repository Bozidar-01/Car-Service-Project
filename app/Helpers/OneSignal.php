<?php
namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
class OneSignal
{
    static function sendNotificationTo($user, $title, $content): bool
    {
        $client = new Client([
            'base_uri' => 'https://api.onesignal.com/',
        ]);

        $data = [
            'app_id' => env('ONE_SIGNAL_APP_ID'),
            'headings' => ['en' => $title],
            'contents' => ['en' => $content],
            'include_external_user_ids' => ['carServices-' . $user],
        ];
        try {
            $response = $client->request('POST', 'notifications?c=push', [
                'headers' => [
                    'Authorization' => 'Basic ' . env('ONE_SIGNAL_AUTHORIZE'),
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
                'body' => json_encode($data),
            ]);

            $body = $response->getBody()->getContents();
            $result = json_decode($body, true);

            // Check for success
            if ($response->getStatusCode() === 200 && isset($result['id'])) {
                echo $body . PHP_EOL;
                return true;
            }

            echo "Failed: " . $body . PHP_EOL;
            return false;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                echo "Error: " . $e->getResponse()->getBody()->getContents() . PHP_EOL;
            } else {
                echo "Request failed: " . $e->getMessage() . PHP_EOL;
            }
            return false;
        }
    }

}
