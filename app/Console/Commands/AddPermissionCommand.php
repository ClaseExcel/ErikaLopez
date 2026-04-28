<?php

namespace App\Console\Commands;

use function Laravel\Prompts\Select;
use Illuminate\Console\Command;

class AddPermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para agregar un permiso a la tabla de permisos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = select(
            label: 'What is your name?',
            options : [
                'a',
                'b',
            ] 
        );
    }
}
