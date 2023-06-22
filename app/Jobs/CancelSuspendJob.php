<?php

namespace App\Jobs;

use App\Mail\CancelSuspensionNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CancelSuspendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->user->is_suspended && $this->user->suspended_until !== null) {
            $suspendedUntil = Carbon::parse($this->user->suspended_until);
            $currentTime = Carbon::now();

            if ($currentTime->gte($suspendedUntil)) {
                $this->user->is_suspended = 0;
                $this->user->suspended_until = null;
                $this->user->save();

                Mail::to($this->user->email)->send(new CancelSuspensionNotification($this->user));
            } else {
            }
        }
    }
}
