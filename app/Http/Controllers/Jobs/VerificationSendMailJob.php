<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Mail\VerificationMail;
use App\Http\Controllers\Api\UserController;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class VerificationSendMailJob implements ShouldQueue

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
      Mail::to($this->data['email'])->send(new VerificationMail($this->data));

      $user = new UserController;
      $user->updateBalance($this->data['email'], 'sms');
    }
}