<?php

namespace jnanagni\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use jnanagni\User;

class BulkWelcome extends Mailable {
    use Queueable, SerializesModels;

    const WELCOME = 0;
    const VWELCOME = 1;

    protected $user;
    protected $emode;
    protected $dataArr;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $_emode = BulkWelcome::WELCOME) {
        $this->user = $user; $this->emode = $_emode;
        $this->dataArr = [
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'schedule' => 'https://drive.google.com/open?id=0B36uSFHMfl6tb3hqWEowTTkyNXc',
            'brochure' => 'https://drive.google.com/open?id=0B36uSFHMfl6tVm5iaDVmVUk0WTQ'
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        switch ($this->emode) {
            case BulkWelcome::WELCOME:
                return $this->view('mail.post-verify-bulk')->with($this->dataArr)
                            ->subject('Welcome');

            case BulkWelcome::VWELCOME:
                return $this->view('mail.post-verify')->with($this->dataArr)
                            ->subject('Welcome');
        }
    }
}
