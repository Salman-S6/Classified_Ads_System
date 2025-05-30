<?php
namespace App\Jobs;

use App\Models\Ad;
use App\Mail\AdConfirmationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAdConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ad;

    public function __construct(Ad $ad)
    {
        $this->ad = $ad;
    }

    public function handle()
    {
        Mail::to($this->ad->user->email)->send(new AdConfirmationMail($this->ad));
    }
}
