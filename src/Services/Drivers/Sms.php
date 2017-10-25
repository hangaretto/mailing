<?php

namespace Magnetar\Mailing\Services\Drivers;

use Magnetar\Mailing\Services\Drivers\Sms\IqDriver;

class Sms
{
    /**
     * Send sms.
     *
     * @param array $data
     * @param bool $is_system
     * @return array|null
     */
    public static function send($data, $is_system = true) {

        switch ($data['driver']) {
            case 'iq':
                if ($is_system == true) {

                    if(!isset($data['login']))
                        $data['login'] = config('magnetar.mailing.sms.iq.login');
                    if(!isset($data['password']))
                        $data['password'] = config('magnetar.mailing.sms.iq.password');
                    if(!isset($data['sender']))
                        $data['sender'] = config('magnetar.mailing.sms.iq.sender');
                    if(!isset($data['statusQueueName']))
                        $data['statusQueueName'] = config('magnetar.mailing.sms.iq.statusQueueName');

                }

                return IqDriver::send($data);
                break;
            default:
                return null;
        }

    }
}