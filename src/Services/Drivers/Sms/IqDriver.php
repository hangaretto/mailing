<?php
namespace Magnetar\Mailing\Services\Drivers\Sms;

use Magnetar\Log\Services\LogServices;

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
     *                      $data['sender'];
     *                      $data['statusQueueName'];
     * @return array
     * @throws
     */
    public static function send($data)
    {

        $trans = array(" " => "", "(" => "", ")" => "", "+" => "", "-" => "");
        $data['phone'] = '+'.substr_replace(strtr($data['phone'], $trans), '7', 0, 1);
        $json = json_encode([
            'messages' => [
                'phone' => $data['phone'],
                'sender' => $data['sender'],
                'client_id' => 1,
                'text' => $data['text']
            ],
            'login' => $data['login'],
            'password' => $data['password'],
            'statusQueueName' => $data['statusQueueName'],
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

        if (empty($body)) {
            LogServices::send('admin_log', ['text' => self::ERROR_EMPTY_RESPONSE . '(Magnetar\Mailing\Services\Drivers\Sms\IqDriver)']);
            return ['success' => false];
        } else if (substr_count($body, 'accepted') == 0) {
            LogServices::send('admin_log', ['text' => $body . '(Magnetar\Mailing\Services\Drivers\Sms\IqDriver)']);
            return ['success' => false, 'data' => json_decode($body, true)];
        }

        return ['success' => true, 'data' => json_decode($body, true)];

    }

}