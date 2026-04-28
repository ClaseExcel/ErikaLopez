<?php

namespace Database\Seeders;

use App\Models\ActividadCliente;
use App\Models\DepartamentosCiudades;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            CargoSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            //new
            
            FrecuenciaSeeder::class,
            EmpresaSeeder::class,
            ModalidadSeeder::class,
            TipoRequerimientoSeeder::class,
            EstadoRequerimientoSeeder::class,
            ActividadSeeder::class,
            ResponsableSeeder::class,  
            EstadoActividadSeeder::class,
            ObligacionesDianTableSeeder::class,
            MunicipiosCiudadesTableSeeder::class,
            CamarasComercioTableSeeder::class,
            FestivosSeeder::class,
            ObligacionesMunicipalesDianTableSeeder::class,
            OtrasEntidadesCTTableSeeder::class,
            DetalleTributarioTableSeeder::class,
            CodigoCiiuTableSeeder::class, 
            UVTTableSeeder::class,
            ActividadesChecklistEmpresasSeeder::class,
            AgrupacionesNIIFSeeder::class,
            OrdeninformesSeeder::class,
            OrdeninformesTableSeeder::class,
            ConceptoTableSeeder::class,
        ]);
    }
}
