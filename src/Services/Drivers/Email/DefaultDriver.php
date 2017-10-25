<?php
namespace Magnetar\Mailing\Services\Drivers\Email;

use Magnetar\Log\Services\LogServices;
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
     * @return array
     * @throws
     */
    public static function send($data)
    {
        try {

            Mail::send([], [], function($message) use($data) {
                $message->setBody($data['html'], 'text/html');
                $message->to($data['to']);
                $message->subject($data['subject']);
                $message->from($data['from'], $data['from_name']);
            });

            return ['success' => true];
        } catch(\Exception $ex) {
            LogServices::send('admin_log', ['text' => $ex->getMessage().'(Magnetar\Mailing\Services\Drivers\Email\DefaultDriver)']);
        }
        return null;
    }
}