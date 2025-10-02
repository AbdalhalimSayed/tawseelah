<?php

namespace App\Listeners;

use App\Events\ForgetPasswordEvent;
use App\Mail\PasswordResetMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ForgetPasswordEvent $event): void
    {
        Mail::to($event->passwordReset->email)->send(new PasswordResetMail($event->passwordReset));
    }
}
