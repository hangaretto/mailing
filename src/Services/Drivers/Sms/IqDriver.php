<?php
namespace Magnetar\Mailing\Services\Drivers\Sms;

class IqDriver
{
    const ERROR_EMPTY_RESPONSE = 'errorEmptyResponse';

    protected static $url = 'http://api.iqsms.ru/messages/v2/send.json';

    /**
     * Send sms.
     *
     * $params array $data
     *                      $data['login'];
     *                      $data['password'];
     *                      $data['phone'];
     *                      $data['text'];
     * @return string
     * @throws
     */
    public static function send($data)
    {

        $trans = array(" " => "", "(" => "", ")" => "", "+" => "", "-" => "");
        $data['phone'] = '+'.substr_replace(strtr($data['phone'], $trans), '7', 0, 1);
        $json = json_encode([
            'messages' => [
                'phone' => $data['phone'],
                'sender' => 'SMS DUCKOHT',
                'client_id' => 1,
                'text' => $data['text']
            ],
            'login' => $data['login'],
            'password' => $data['password'],
            'statusQueueName' => 'testQueue',
        ]);

        $client = curl_init(self::$url);
        curl_setopt_array($client, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_POSTFIELDS => $json,
        ));

        $body = curl_exec($client);
        curl_close($client);

        if (empty($body))
            \Log::error(self::ERROR_EMPTY_RESPONSE);
        else if(substr_count($body, 'accepted') == 0)
            \Log::error($body);

        return $body;

    }

}