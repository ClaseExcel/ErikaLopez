<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\CotizacionController;
use Illuminate\Console\Command;

class NotificacionSeguimientoCotizacion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exec:seguimiento_cotizacion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manda notificación de correo para el seguimiento de una cotización';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $actividadCliente = new CotizacionController();
        $actividadCliente->emailSeguimiento();
        return Command::SUCCESS;
    }
}
