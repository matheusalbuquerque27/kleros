<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AtualizarFeedsJob;

class AtualizarFeedsCommand extends Command
{
    protected $signature = 'feeds:atualizar';
    protected $description = 'Carrega e atualiza os feeds em background';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AtualizarFeedsJob::dispatch();
        $this->info('Feeds enviados para atualização.');
    }
}
