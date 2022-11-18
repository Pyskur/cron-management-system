<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Mail\LeadMail;
use App\Http\Controllers\Api\UserController;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class LeadSendmailJob implements ShouldQueue

{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public function __construct(array $data)
    {
        $this -> data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Mail::to('daniellihm03@gmail.com')->queue(new LeadMail($this->data));

      $user = new UserController;
      $user->updateBalance('daniellihm03@gmail.com', 'Otp');
    }
}