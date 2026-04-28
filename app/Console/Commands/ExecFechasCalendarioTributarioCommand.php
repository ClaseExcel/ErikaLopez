<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\FechasCalendarioTributarioController;
use Illuminate\Console\Command;

class ExecFechasCalendarioTributarioCommand extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exec:fechas_calendario_tributario {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se llena la informacion con las fechas en la que se tiene que presentar impuestos por empresa';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        FechasCalendarioTributarioController::Datos($id);
        FechasCalendarioTributarioController::Datosmunicipales($id);
        FechasCalendarioTributarioController::Datosotrasentidades($id);
        return Command::SUCCESS;
    }
}
