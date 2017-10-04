<?php

namespace Magnetar\Mailing\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class Mailing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove/buy expired tariffs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    }

}
