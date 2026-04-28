<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [                
                'title'  => 'Super Administrador'
            ],
            [                
                'title'  => 'Administrador'
            ],
            [                
                'title'  => 'Contador Senior'
            ],
            [                
                'title'  => 'Contador Junior'
            ],
            [                
                'title'  => 'Analista'
            ],
            [                
                'title'  => 'Auditor'
            ],
            [                
                'title'  => 'Cliente'
            ],
            [                
                'title'  => 'Génerico'
            ],
            
        ];

        Role::insert($roles);
    }
}
