<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetCode extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $code;
    public $congregacao;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $code, $congregacao)
    {
        $this->user = $user;
        $this->code = $code;
        $this->congregacao = $congregacao;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Código de recuperação de senha')
            ->view('emails.auth.password-reset-code', [
                'user' => $this->user,
                'code' => $this->code,
                'congregacao' => $this->congregacao,
            ]);
    }
}
