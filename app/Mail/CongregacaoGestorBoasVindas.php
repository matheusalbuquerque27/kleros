<?php

namespace App\Mail;

use App\Models\Congregacao;
use App\Models\Membro;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CongregacaoGestorBoasVindas extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Congregacao $congregacao,
        public User $gestor,
        public ?Membro $membro,
        public string $senhaTemporaria
    ) {
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $loginUrl = route('login', [], true);

        return $this
            ->subject(__('congregations.emails.gestor_welcome.subject'))
            ->view('emails.congregacoes.gestor-boas-vindas')
            ->with([
                'congregacao' => $this->congregacao,
                'gestor' => $this->gestor,
                'membro' => $this->membro,
                'senhaTemporaria' => $this->senhaTemporaria,
                'loginUrl' => $loginUrl,
            ]);
    }
}
