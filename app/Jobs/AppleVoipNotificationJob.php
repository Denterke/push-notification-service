<?php

namespace App\Jobs;

use App\Builders\VoipNotificationBuilder;
use App\Packages\IosNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AppleVoipNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $data;
    private $apnsConnection;

    public function __construct($data)
    {
        $this->data = $data;
        $this->queue = 'apple-voip-notifications';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* @var $apnsConnection IosNotification**/
        $apnsConnection = app(IosNotification::class);
        $builder = new VoipNotificationBuilder($this->data);
        $apnsConnection->sendNotification($builder);
    }
}
