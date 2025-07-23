<?php

namespace App\Console\Commands;

use App\Http\Controllers\DeputadosController;
use Illuminate\Console\Command;

class DeputadosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:deputados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatiza o JOB para registro de debutados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new DeputadosController();

        $controller->index();

        $this->info('Deputados atualizados com sucesso!');
    }
}
