<?php

namespace Magnetar\Mailing\Services;

use Magnetar\Mailing\Services\Drivers\Email\DefaultDriver;
use Magnetar\Mailing\Services\Drivers\Email\ElasticDriver;
use Magnetar\Mailing\Services\Drivers\Sms\IqDriver;
use Mail;

class MailingServices {

    /**
     * Create mailing job.
     *
     * @param string $type
     * @param string $type
     * @param array $data
     * @return bool
     */
    public static function send($type, $data, $is_system = true) {

        switch ($type) {
            case 'sms':
                switch ($data['driver']) {
                    case 'iq':
                        if ($is_system == true) {
                            $data['login'] = config('magnetar.mailing.sms.iq.login');
                            $data['password'] = config('magnetar.mailing.sms.iq.password');
                        }

                        IqDriver::send($data);
                        break;
                    default:
                        return false;
                }
                break;
            case 'email':
                if($is_system == true) {
                    $data['from'] = config('mail.from.address');
                    $data['from_name'] = config('mail.from.name');
                    $data['driver'] = config('mail.driver');
                }

                switch ($data['driver']) {
                    case 'elastic':
                        if($is_system == true) {
                            $data['api_key'] = config('magnetar.mailing.email.elastic.api_key');
                            $data['username'] = config('magnetar.mailing.email.elastic.username');
                        }

                        ElasticDriver::send($data);
                        break;
                    default:
                        DefaultDriver::send($data);
                        break;
                }
                break;
            default:
                return false;
        }

        return true;
    }

}
