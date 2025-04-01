<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Ladumor\OneSignal\OneSignal;

class NotificationsController extends Controller
{
    public function sendNotification($user)
    {
        $userID = $user->id;
        $fields['include_external_user_ids'] = ['mangoCare-'.$userID];
        $notificationMsg = 'An answer to your question has been posted!';
        OneSignal::sendPush($fields, $notificationMsg);
    }
}
