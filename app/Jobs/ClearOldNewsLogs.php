<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\News;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ClearOldNewsLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logLastingPeriod = config('news.log_period');

        News::where('created_at', '<', Carbon::now()->subDays($logLastingPeriod))->delete();
    }
}
