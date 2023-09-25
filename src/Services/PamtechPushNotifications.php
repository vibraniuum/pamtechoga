<?php

namespace Vibraniuum\Pamtechoga\Services;

use Vibraniuum\Pamtechoga\Models\DeviceToken;

class PamtechPushNotifications
{
    public function sendNotification($title, $body, $devicesToSendTo = null)
    {
        if (is_null($devicesToSendTo)) {
            $devices = DeviceToken::all()->pluck('device_token');
        } else {
            $devices = $devicesToSendTo;
        }

        $SERVER_API_KEY = env('FCM_SERVER_KEY');

        $data = [
            "registration_ids" => $devices,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
    }
}
