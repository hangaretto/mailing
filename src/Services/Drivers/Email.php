<?php

namespace Magnetar\Mailing\Services\Drivers;

use Magnetar\Mailing\Services\Drivers\Email\DefaultDriver;
use Magnetar\Mailing\Services\Drivers\Email\ElasticDriver;

class Email
{
    /**
     * Send email.
     *
     * @param array $data
     * @param bool $is_system
     * @return string|null
     */
    public static function send($data, $is_system = true) {

        if($is_system == true) {

            if(!isset($data['from']))
                $data['from'] = config('mail.from.address');
            if(!isset($data['from_name']))
                $data['from_name'] = config('mail.from.name');
            if(!isset($data['driver']))
                $data['driver'] = config('mail.driver');

        }

        switch ($data['driver']) {
            case 'elastic_email':
                if($is_system == true) {

                    if(!isset($data['api_key']))
                        $data['api_key'] = config('magnetar.mailing.email.elastic_email.api_key');
                    if(!isset($data['username']))
                        $data['username'] = config('magnetar.mailing.email.elastic_email.username');

                }

                return ElasticDriver::send($data);
                break;
            default:
                return DefaultDriver::send($data);
                break;
        }

    }
}