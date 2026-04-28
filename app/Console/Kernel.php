<?php

namespace App\Console;

use App\Models\Empresa;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('exec:expired_activities')->dailyAt('00:00');
        $schedule->command('exec:vencimiento-requerimientos')->dailyAt('00:00');
        $schedule->command('exec:email_actividad_cliente')->dailyAt('00:15');
         $schedule->command('exec:notification')->dailyAt('00:15');
        $schedule->command('exec:notification_whatsapp')->dailyAt('00:30');
        $schedule->command('exec:seguimiento_cotizacion')->dailyAt('00:30');
       
       
        $schedule->command('backup:clean')->dailyAt('20:00');
        $schedule->command('backup:run --only-db')->dailyAt('20:15');

        //EJECUTAR  maestro Fechas calendario tributario por empresa
        $companias = Empresa::pluck('id')->toArray();
        foreach ($companias as $compania) {
            $schedule->command("exec:fechas_calendario_tributario {$compania}")->dailyAt('00:45');
            $schedule->command("exec:correofechasmasivo {$compania}")->monthlyOn(1, '11:00'); 
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
