<?php

namespace App\Console\Commands;


use App\Http\Controllers\NotificationController;
use Illuminate\Console\Command;

class ExecNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exec:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se envian notificaciones a todos los usuarios';

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
        $notificaciones = new NotificationController();
        $notificaciones->sendExpiredActivities();
        return Command::SUCCESS;
    }
}
