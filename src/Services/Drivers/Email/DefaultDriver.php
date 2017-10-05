<?php
namespace Magnetar\Mailing\Services\Drivers\Email;

use Mail;

class DefaultDriver
{

    /**
     * Send sms.
     *
     * $params array $data
     *                      $data['from'];
     *                      $data['from_name'];
     *                      $data['subject'];
     *                      $data['to'];
     *                      $data['html'];
     * @return string
     * @throws
     */
    public static function send($data)
    {
        try{
            Mail::send([], [], function($message) use($data) {
                $message->setBody($data['html'], 'text/html');
                $message->to($data['to']);
                $message->subject($data['subject']);
                $message->from($data['from'], $data['from_name']);
            });
        }
        catch(\Exception $ex){
            \Log::error($ex->getMessage());
        }
        return null;
    }
}