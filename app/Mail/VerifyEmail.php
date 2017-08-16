<?php

namespace jnanagni\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use jnanagni\User;

class VerifyEmail extends Mailable {
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('mail.verify')
                    ->with([
                        'hash' => $this->user->email_hash,
                        'token' => $this->user->token
                    ])
                    ->subject('Finalize Registration');
    }
}
