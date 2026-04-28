<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\RequerimientoEmpleadoController;
use Illuminate\Console\Command;

class VencimientoRequerimientos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exec:vencimiento-requerimientos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vence los requerimientos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $requerimiento = new RequerimientoEmpleadoController();
        $requerimiento->vencimientoRequerimiento();
        return Command::SUCCESS;
    }
}
