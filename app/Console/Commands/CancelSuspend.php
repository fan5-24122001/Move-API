<?php

namespace App\Console\Commands;

use App\Mail\CancelSuspensionNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CancelSuspend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:cancelSuspend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::where('is_suspended', 1)->whereDate('suspended_until', '<=', Carbon::now())->get();

        foreach ($users as $reportedUser) {
            $reportedUser->is_suspended = 0;
            $reportedUser->suspended_until = null;
            $reportedUser->save();
            Mail::to($reportedUser->email)->send(new CancelSuspensionNotification($reportedUser));
        }
    }
}
