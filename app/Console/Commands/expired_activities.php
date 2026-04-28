<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\ActividadClienteController;

class expired_activities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exec:expired_activities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza las actividades que ya pasaron su fecha de vencimiento';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $actividadCliente = new ActividadClienteController();
        $actividadCliente->update_activities();
        return Command::SUCCESS;
    }
}
