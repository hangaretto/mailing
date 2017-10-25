<?php

namespace Magnetar\Mailing\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Magnetar\Mailing\Services\Drivers\Email;
use Magnetar\Mailing\Services\Drivers\Sms;

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
     * @return string|null
     */
    public function handle()
    {
        switch ($this->type) {
            case 'sms':
                return Sms::send($this->data, $this->is_system);
                break;
            case 'email':
                return Email::send($this->data, $this->is_system);
                break;
            default:
                return null;
        }
    }
}
