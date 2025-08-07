<?php

namespace App\Helpers\Traits;

trait SMSTrait
{
    private function techno_bulk_sms($apKey, $senderId, $mobileNo, $message, $userEmail)
    {
        $url = 'https://24bulksms.com/24bulksms/api/api-sms-send';
        $data = array(
            'api_key' => $apKey,
            'sender_id' => $senderId,
            'message' => $message,
            'mobile_no' => $mobileNo,
            'user_email' => $userEmail
        );

        // Initialize cURL
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        // Execute cURL and capture the response
        $output = curl_exec($curl);

        // Close cURL
        curl_close($curl);

        // Print output (optional)
        // print_r($output);
    }
}
