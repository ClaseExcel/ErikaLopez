<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\ActividadClienteController;
use Illuminate\Console\Command;

class ExecActividadClienteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exec:email_actividad_cliente';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se envian notificaciones de actividades clientes';

    // /**
    //  * Create a new command instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     parent::__construct();
    // }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $actividadCliente = new ActividadClienteController();
        $actividadCliente->activity_notification();
        return Command::SUCCESS;
    }
}
