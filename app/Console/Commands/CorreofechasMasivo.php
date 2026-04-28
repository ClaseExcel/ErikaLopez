<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\CalendarioTributarioController;
use Illuminate\Console\Command;

class CorreofechasMasivo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exec:correofechasmasivo {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta el método correofechasmasivo para una empresa específica';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Obtener el parámetro `id`
        $id = $this->argument('id');
        CalendarioTributarioController::correofechasmasivo($id);
        return Command::SUCCESS;
    }
}
