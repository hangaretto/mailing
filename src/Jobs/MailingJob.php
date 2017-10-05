<?php

namespace Magnetar\Mailing\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Magnetar\Mailing\Services\Drivers\Email\DefaultDriver;
use Magnetar\Mailing\Services\Drivers\Email\ElasticDriver;
use Magnetar\Mailing\Services\Drivers\Sms\IqDriver;

class MailingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $data;
    protected $is_system;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $data, $is_system = true)
    {
        $this->type = $type;
        $this->data = $data;
        $this->is_system = $is_system;
    }

    /**
     * Execute mailing job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->type) {
            case 'sms':
                switch ($this->data['driver']) {
                    case 'iq':
                        if ($this->is_system == true) {
                            $this->data['login'] = config('magnetar.mailing.sms.iq.login');
                            $this->data['password'] = config('magnetar.mailing.sms.iq.password');
                        }

                        IqDriver::send($this->data);
                        break;
                    default:
                        return false;
                }
                break;
            case 'email':
                if($this->is_system == true) {
                    $this->data['from'] = config('mail.from.address');
                    $this->data['from_name'] = config('mail.from.name');
                    $this->data['driver'] = config('mail.driver');
                }

                switch ($this->data['driver']) {
                    case 'elastic':
                        if($this->is_system == true) {
                            $this->data['api_key'] = config('magnetar.mailing.email.elastic.api_key');
                            $this->data['username'] = config('magnetar.mailing.email.elastic.username');
                        }

                        ElasticDriver::send($this->data);
                        break;
                    default:
                        DefaultDriver::send($this->data);
                        break;
                }
                break;
            default:
                return false;
        }

        return true;
    }
}
