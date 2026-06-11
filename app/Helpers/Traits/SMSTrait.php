<?php

namespace App\Helpers\Traits;

trait SMSTrait
{
    /**
     * Generate Access Token
     */
    private function getAdaReachToken()
    {
        $url = "https://api.mobireach.com.bd/auth/tokens";

        $payload = [
            'username' => 'speak',
            'password' => 'Dhaka@00888944',
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        return $result['token'] ?? false;
    }

    /**
     * Send SMS
     */
    public function sendSMS($mobileNo, $message)
    {
        $token = $this->getAdaReachToken();

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Token generation failed'
            ];
        }

        $url = "https://api.mobireach.com.bd/sms/send";

        $payload = [
            "sender" => "Speak Up BD", // আপনার Sender ID
            "receiver" => [
                $mobileNo
            ],
            "content" => $message,
            "msgType" => "T",
            "requestType" => "S",
            "contentType" => 1
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);

            curl_close($ch);

            return [
                'success' => false,
                'message' => $error
            ];
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}