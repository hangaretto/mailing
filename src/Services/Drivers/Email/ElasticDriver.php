<?php
namespace Magnetar\Mailing\Services\Drivers\Email;

use Magnetar\Log\Services\LogServices;
use Magnetar\Mailing\Services\HtmlToText;

class ElasticDriver
{
    protected static $url = 'https://api.elasticemail.com/v2/email/send';

    /**
     * Send sms.
     *
     * $params array $data
     *                      $data['from'];
     *                      $data['from_name'];
     *                      $data['apiKey'];
     *                      $data['username'];
     *                      $data['subject'];
     *                      $data['to'];
     *                      $data['html'];
     * @return array
     * @throws
     */
    public static function send($data)
    {
        try {

            $html = new HtmlToText($data['html']);
            $post = [
                'apikey' => $data['api_key'],
                'username' => $data['username'],
                'from' => $data['from'],
                'fromName' => $data['from_name'],
                'subject' => $data['subject'],
                'to' => $data['to'],
                'body_html' => $data['html'],
                'body_text' => $html->getText(),
                'isTransactional' => false
            ];

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => self::$url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $post,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_SSL_VERIFYPEER => false
            ]);

            $result=curl_exec ($ch);
            curl_close ($ch);

            $result = json_decode($result, true);

            return $result;
        } catch(\Exception $ex) {
            LogServices::send('admin_log', ['text' => $ex->getMessage().'(Magnetar\Mailing\Services\Drivers\Email\ElasticDriver)']);
        }

        return null;
    }
}