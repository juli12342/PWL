<?php 

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
  use Queueable, SerializesModels;

  public function build()
  {
    return $this->from(env('MAIL_FROM_ADDRESS'))
        -> view('mail/test_mail');
  }
}
