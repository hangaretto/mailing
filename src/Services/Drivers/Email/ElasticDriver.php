<?php
namespace Magnetar\Mailing\Services\Drivers\Email;

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
     * @return string
     * @throws
     */
    public static function send($data)
    {
        try{

            $html = new HtmlToText($data['html']);
            $post = [
                'apikey' => $data['apiKey'],
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

            return $result;
        }
        catch(\Exception $ex){
            \Log::error($ex->getMessage());
        }

        return null;
    }
}