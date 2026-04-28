<?php

namespace App\Services;

use App\Models\CentroCosto;
use App\Models\Clientes;
use App\Models\orden_compania_informes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InformeBalanceCuentasServices
{
        /**
     * Genera un informe PYME y PYC combinando datos de clientesmovimientos y datos de modificación.
     *
     * @param string $nit NIT de la compañía.
     * @param string $fecha Fecha del informe.
     * @param array|null $datos2 Datos de modificación (opcional).
     * @return \Illuminate\Support\Collection Informe PYME y PYC combinado.
     */
    public function ejecutar($nit, $fecha, $datos2 = null,$siigo)
    {
              // Verifica el tipo de compañía
        if ($siigo == 'NUBE') {
            // Si es una compañía en la nube, obtiene datos de modificación y genera un informe PYC para la nube
            
            $datos = $this->informepycnube($nit, $fecha, $datos2);
        } else if($siigo =='PYME'){
            // Si no es una compañía en la nube, obtiene datos de modificación y genera un informe PYME o PYC
            $datos = $this->informepymepyc($nit, $fecha, $datos2);
        }else if($siigo =='CONTAPYME'){
            $datos =  $this->informecontapymepyc($nit, $fecha, $datos2);
        }else if($siigo =='LOGGRO'){
            $datos = $this->informeloggropyc($nit, $fecha, $datos2);
        }else if($siigo =='BEGRANDA'){
            $datos = $this->informebegrandapyc($nit, $fecha, $datos2);
        }else {
            $datos = $this->informewimaxpyc($nit, $fecha, $datos2);
        }


        return $datos;
    }

    private function informepymepyc($nit, $fecha, $datos2 = null)
    {
        $datos = Clientes::selectRaw("
                REPLACE(CONCAT(
                    TRIM(IFNULL(clientes.grupo, '')),
                    TRIM(IFNULL(clientes.cuenta, '')),
                    TRIM(IFNULL(clientes.subcuenta, ''))
                ), ' ', '') AS cuenta
            ")
            ->selectRaw("MAX(TRIM(IFNULL(clientes.descripcion, ''))) AS nombre_orden_informes")
            ->selectRaw("ROUND(SUM(clientes.saldo_anterior), 0) AS saldoinicial")
            ->selectRaw("ROUND(SUM(clientes.debitos), 0) AS debitos")
            ->selectRaw("ROUND(SUM(clientes.creditos), 0) AS creditos")
            ->selectRaw("ROUND(SUM(clientes.nuevo_saldo), 0) AS saldo_mov")
            ->selectRaw("ROUND(SUM(IFNULL(clientes.nuevo_saldo, 0)), 2) AS total_mes")
            ->where('clientes.Nit', $nit)
            ->whereYear('clientes.fechareporte', date('Y', strtotime($fecha)))
            ->whereMonth('clientes.fechareporte', date('m', strtotime($fecha)))
            ->where(function($query) {
                $query->where(function($q) {
                    $q->whereRaw("TRIM(IFNULL(clientes.cuenta, '')) = ''")
                    ->whereRaw("TRIM(IFNULL(clientes.subcuenta, '')) = ''");
                })->orWhereRaw("TRIM(IFNULL(clientes.subcuenta, '')) = ''");
            })
            ->whereRaw("
                REPLACE(CONCAT(
                    TRIM(IFNULL(clientes.grupo, '')),
                    TRIM(IFNULL(clientes.cuenta, '')),
                    TRIM(IFNULL(clientes.subcuenta, ''))
                ), ' ', '') <> ''
            ")
            ->groupByRaw("
                REPLACE(CONCAT(
                    TRIM(IFNULL(clientes.grupo, '')),
                    TRIM(IFNULL(clientes.cuenta, '')),
                    TRIM(IFNULL(clientes.subcuenta, ''))
                ), ' ', '')
            ")
            ->orderBy('cuenta', 'ASC')
            ->get();

            return $datos;


    }

    private function informecontapymepyc($nit, $fecha, $datos2 = null)
    {
        // Obtiene la orden de la compañía (asumiendo que solo hay un registro por NIT)
        $cuentas = orden_compania_informes::select('orden')->where('nit', $nit)->get();
        $orden = $cuentas->pluck('orden')->first();
      // Subconsulta para obtener los datos necesarios
        $subquery = DB::table('contapyme_completo as contapyme')
        ->selectRaw('contapyme.cuenta')
        ->selectRaw('COALESCE(
            CASE
                WHEN contapyme.cuenta = "5150" THEN "Adecuación e instalación"
                ELSE (
                    SELECT MAX(oi.nombre) 
                    FROM ordeninformes oi 
                    WHERE contapyme.cuenta = oi.agrupador_cuenta
                )
            END,
            contapyme.descripcion
        ) AS nombre_orden_informes')
        ->selectRaw('contapyme.saldo_anterior')
        ->selectRaw('contapyme.debitos')
        ->selectRaw('contapyme.creditos')
        ->selectRaw('contapyme.nuevo_saldo')
        ->where('contapyme.Nit', $nit)
        ->whereRaw('YEAR(contapyme.fechareporte) = YEAR(?)', [$fecha])
        ->whereRaw('MONTH(contapyme.fechareporte) = MONTH(?)', [$fecha])
        ->where(function ($query) use ($orden) {
            $ordenArray = json_decode($orden, true);
            foreach ($ordenArray as $codigo) {
                $query->orWhere('contapyme.cuenta', '=', $codigo);
            }
        });


        // Consulta principal utilizando la subconsulta
        $datos = DB::table(DB::raw("({$subquery->toSql()}) as subquery"))
        ->mergeBindings($subquery) // Asegura que los parámetros de la subconsulta se hereden
        ->selectRaw('MAX(subquery.cuenta) as cuenta')
        ->selectRaw('subquery.nombre_orden_informes')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.saldo_anterior), 2)) as saldoinicial')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.debitos), 2)) as debitos')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.creditos), 2)) as creditos')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.nuevo_saldo), 2)) as saldo_mov')
        ->selectRaw('ROUND(SUM(subquery.saldo_anterior) - SUM(subquery.nuevo_saldo), 2) AS total_mes')
        ->groupBy('subquery.nombre_orden_informes')
        ->orderBy('cuenta', 'ASC')
        ->get();
        // Organiza los datos de modificación por "nombre_orden_informes"
        $modificadosPorNombre = [];
        if (!empty($datos2)) {
            foreach ($datos2 as $modificado) {
                $nombre = $modificado->nombre_orden_informes;
                if (!isset($modificadosPorNombre[$nombre])) {
                    $modificadosPorNombre[$nombre] = '0'; // Inicializa a '0' si el nombre no existe como cadena
                }

                // Eliminar comas y puntos del valor antes de usar BCMath
                $valorAjustado = str_replace(['.', ','], '', $modificado->valorajustado);

                // Luego, convierte el valor a un número con BCMath
                $modificadosPorNombre[$nombre] = bcadd($modificadosPorNombre[$nombre], $valorAjustado, 2);
            }

            // Actualiza los valores en $datos con los valores de modificación usando el nombre
            foreach ($datos as &$filaPymepyc) {
                $nombre = $filaPymepyc->nombre_orden_informes;
                if (isset($modificadosPorNombre[$nombre])) {
                    $valorModificado = $modificadosPorNombre[$nombre];
                    $valorExistente = $filaPymepyc->nuevo_saldo;

                    // Elimina comas y puntos de los valores y conviértelos a números decimales
                    $valorModificadoDecimal = bcadd(str_replace(['.', ','], '', $valorModificado), '0', 2);
                    $valorExistenteDecimal = bcadd(str_replace(['.', ','], '', $valorExistente), '0', 2);

                    // Realiza la suma con BCMath y convierte el resultado a cadena con formato deseado
                    $nuevoSaldoDecimal = bcadd($valorExistenteDecimal, $valorModificadoDecimal, 2);
                    $filaPymepyc->nuevo_saldo = number_format($nuevoSaldoDecimal, 2, '.', ',');
                }
            }
        }

        return $datos;
    }

    private function informeloggropyc($nit, $fecha, $datos2 = null)
    {
        // Obtiene la orden de la compañía (asumiendo que solo hay un registro por NIT)
        $cuentas = orden_compania_informes::select('orden')->where('nit', $nit)->get();
        $orden = $cuentas->pluck('orden')->first();
      
        // Subconsulta para obtener los datos necesarios de loggro
        $subquery = DB::table('loggro')
        ->selectRaw('loggro.cuenta')
        ->selectRaw('COALESCE(
            CASE
                WHEN loggro.cuenta = "5150" THEN "Adecuación e instalación"
                ELSE (
                    SELECT MAX(oi.nombre) 
                    FROM ordeninformes oi 
                    WHERE loggro.cuenta = oi.agrupador_cuenta
                )
            END,
            loggro.descripcion
        ) AS nombre_orden_informes')
        ->selectRaw('loggro.saldo_anterior')
        ->selectRaw('loggro.debitos')
        ->selectRaw('loggro.creditos')
        ->selectRaw('loggro.neto as nuevo_saldo') // Asumí que 'neto' es el nuevo saldo en loggro
        ->where('loggro.Nit', $nit)
        ->whereRaw('YEAR(loggro.fechareporte) = YEAR(?)', [$fecha])
        ->whereRaw('MONTH(loggro.fechareporte) = MONTH(?)', [$fecha])
        ->where(function ($query) use ($orden) {
            $ordenArray = json_decode($orden, true);
            foreach ($ordenArray as $codigo) {
                $query->orWhere('loggro.cuenta', '=', $codigo);
            }
        });

        // Consulta principal utilizando la subconsulta
        $datos = DB::table(DB::raw("({$subquery->toSql()}) as subquery"))
        ->mergeBindings($subquery) // Asegura que los parámetros de la subconsulta se hereden
        ->selectRaw('MAX(subquery.cuenta) as cuenta')
        ->selectRaw('subquery.nombre_orden_informes')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.saldo_anterior), 2)) as saldoinicial')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.debitos), 2)) as debitos')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.creditos), 2)) as creditos')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.nuevo_saldo), 2)) as saldo_mov')
        ->selectRaw('ROUND(SUM(subquery.saldo_anterior) - SUM(subquery.nuevo_saldo), 2) AS total_mes')
        ->groupBy('subquery.nombre_orden_informes')
        ->orderBy('cuenta', 'ASC')
        ->get();
        // Organiza los datos de modificación por "nombre_orden_informes"
        $modificadosPorNombre = [];
        if (!empty($datos2)) {
            foreach ($datos2 as $modificado) {
                $nombre = $modificado->nombre_orden_informes;
                if (!isset($modificadosPorNombre[$nombre])) {
                    $modificadosPorNombre[$nombre] = '0'; // Inicializa a '0' si el nombre no existe como cadena
                }

                // Eliminar comas y puntos del valor antes de usar BCMath
                $valorAjustado = str_replace(['.', ','], '', $modificado->valorajustado);

                // Luego, convierte el valor a un número con BCMath
                $modificadosPorNombre[$nombre] = bcadd($modificadosPorNombre[$nombre], $valorAjustado, 2);
            }

            // Actualiza los valores en $datos con los valores de modificación usando el nombre
            foreach ($datos as &$filaPymepyc) {
                $nombre = $filaPymepyc->nombre_orden_informes;
                if (isset($modificadosPorNombre[$nombre])) {
                    $valorModificado = $modificadosPorNombre[$nombre];
                    $valorExistente = $filaPymepyc->nuevo_saldo;

                    // Elimina comas y puntos de los valores y conviértelos a números decimales
                    $valorModificadoDecimal = bcadd(str_replace(['.', ','], '', $valorModificado), '0', 2);
                    $valorExistenteDecimal = bcadd(str_replace(['.', ','], '', $valorExistente), '0', 2);

                    // Realiza la suma con BCMath y convierte el resultado a cadena con formato deseado
                    $nuevoSaldoDecimal = bcadd($valorExistenteDecimal, $valorModificadoDecimal, 2);
                    $filaPymepyc->nuevo_saldo = number_format($nuevoSaldoDecimal, 2, '.', ',');
                }
            }
        }

        return $datos;
    }

    private function informewimaxpyc($nit, $fecha, $datos2 = null)
    {
       
         $datos = DB::table('informesgenericos')
        ->selectRaw('
            cuenta,
            descripcion AS nombre_orden_informes,
            TRIM(TRAILING ".00" FROM FORMAT(saldo_anterior, 2)) as saldoinicial,
            TRIM(TRAILING ".00" FROM FORMAT(debitos, 2)) as debitos,
            TRIM(TRAILING ".00" FROM FORMAT(creditos, 2)) as creditos,
            TRIM(TRAILING ".00" FROM FORMAT(saldo_final, 2)) as saldo_mov
        ')
        ->where('Nit', $nit)
        ->whereRaw('YEAR(fechareporte) = YEAR(?)', [$fecha])
        ->whereRaw('MONTH(fechareporte) = MONTH(?)', [$fecha])
        ->whereRaw('CHAR_LENGTH(TRIM(cuenta)) <= 4') // asegura máximo 4 dígitos
        ->orderBy('cuenta', 'ASC')
        ->get();
    
    
        // Organiza los datos de modificación por "nombre_orden_informes"
        $modificadosPorNombre = [];
        if (!empty($datos2)) {
            foreach ($datos2 as $modificado) {
                $nombre = $modificado->nombre_orden_informes;
                if (!isset($modificadosPorNombre[$nombre])) {
                    $modificadosPorNombre[$nombre] = '0'; // Inicializa a '0' si el nombre no existe como cadena
                }

                // Eliminar comas y puntos del valor antes de usar BCMath
                $valorAjustado = str_replace(['.', ','], '', $modificado->valorajustado);

                // Luego, convierte el valor a un número con BCMath
                $modificadosPorNombre[$nombre] = bcadd($modificadosPorNombre[$nombre], $valorAjustado, 2);
            }

            // Actualiza los valores en $datos con los valores de modificación usando el nombre
            foreach ($datos as &$filaPymepyc) {
                $nombre = $filaPymepyc->nombre_orden_informes;
                if (isset($modificadosPorNombre[$nombre])) {
                    $valorModificado = $modificadosPorNombre[$nombre];
                    $valorExistente = $filaPymepyc->nuevo_saldo;

                    // Elimina comas y puntos de los valores y conviértelos a números decimales
                    $valorModificadoDecimal = bcadd(str_replace(['.', ','], '', $valorModificado), '0', 2);
                    $valorExistenteDecimal = bcadd(str_replace(['.', ','], '', $valorExistente), '0', 2);

                    // Realiza la suma con BCMath y convierte el resultado a cadena con formato deseado
                    $nuevoSaldoDecimal = bcadd($valorExistenteDecimal, $valorModificadoDecimal, 2);
                    $filaPymepyc->nuevo_saldo = number_format($nuevoSaldoDecimal, 2, '.', ',');
                }
            }
        }

        return $datos;
    }

    private function informebegrandapyc($nit, $fecha, $datos2 = null)
    {
        // Obtiene la orden de la compañía (asumiendo que solo hay un registro por NIT)
        $cuentas = orden_compania_informes::select('orden')->where('nit', $nit)->get();
        $orden = $cuentas->pluck('orden')->first();
      
        // Subconsulta para obtener los datos necesarios de loggro
        $subquery = DB::table('begranda')
        ->selectRaw('begranda.cuenta')
        ->selectRaw('COALESCE(
            CASE
                WHEN begranda.cuenta = "5150" THEN "Adecuación e instalación"
                ELSE (
                    SELECT MAX(oi.nombre) 
                    FROM ordeninformes oi 
                    WHERE begranda.cuenta = oi.agrupador_cuenta
                )
            END,
            begranda.descripcion
        ) AS nombre_orden_informes')
        ->selectRaw('begranda.saldo_anterior')
        ->selectRaw('begranda.debitos')
        ->selectRaw('begranda.creditos')
        ->selectRaw('begranda.saldo_final as nuevo_saldo') // Asumí que 'neto' es el nuevo saldo en begranda
        ->where('begranda.Nit', $nit)
        ->whereRaw('YEAR(begranda.fechareporte) = YEAR(?)', [$fecha])
        ->whereRaw('MONTH(begranda.fechareporte) = MONTH(?)', [$fecha])
        ->where(function ($query) use ($orden) {
            $ordenArray = json_decode($orden, true);
            foreach ($ordenArray as $codigo) {
                $query->orWhere('begranda.cuenta', '=', $codigo);
            }
        });

        // Consulta principal utilizando la subconsulta
        $datos = DB::table(DB::raw("({$subquery->toSql()}) as subquery"))
        ->mergeBindings($subquery) // Asegura que los parámetros de la subconsulta se hereden
        ->selectRaw('MAX(subquery.cuenta) as cuenta')
        ->selectRaw('subquery.nombre_orden_informes')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.saldo_anterior), 2)) as saldoinicial')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.debitos), 2)) as debitos')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.creditos), 2)) as creditos')
        ->selectRaw('TRIM(TRAILING ".00" FROM FORMAT(SUM(subquery.nuevo_saldo), 2)) as saldo_mov')
        ->selectRaw('ROUND(SUM(subquery.saldo_anterior) - SUM(subquery.nuevo_saldo), 2) AS total_mes')
        ->groupBy('subquery.nombre_orden_informes')
        ->orderBy('cuenta', 'ASC')
        ->get();
        // Organiza los datos de modificación por "nombre_orden_informes"
        $modificadosPorNombre = [];
        if (!empty($datos2)) {
            foreach ($datos2 as $modificado) {
                $nombre = $modificado->nombre_orden_informes;
                if (!isset($modificadosPorNombre[$nombre])) {
                    $modificadosPorNombre[$nombre] = '0'; // Inicializa a '0' si el nombre no existe como cadena
                }

                // Eliminar comas y puntos del valor antes de usar BCMath
                $valorAjustado = str_replace(['.', ','], '', $modificado->valorajustado);

                // Luego, convierte el valor a un número con BCMath
                $modificadosPorNombre[$nombre] = bcadd($modificadosPorNombre[$nombre], $valorAjustado, 2);
            }

            // Actualiza los valores en $datos con los valores de modificación usando el nombre
            foreach ($datos as &$filaPymepyc) {
                $nombre = $filaPymepyc->nombre_orden_informes;
                if (isset($modificadosPorNombre[$nombre])) {
                    $valorModificado = $modificadosPorNombre[$nombre];
                    $valorExistente = $filaPymepyc->nuevo_saldo;

                    // Elimina comas y puntos de los valores y conviértelos a números decimales
                    $valorModificadoDecimal = bcadd(str_replace(['.', ','], '', $valorModificado), '0', 2);
                    $valorExistenteDecimal = bcadd(str_replace(['.', ','], '', $valorExistente), '0', 2);

                    // Realiza la suma con BCMath y convierte el resultado a cadena con formato deseado
                    $nuevoSaldoDecimal = bcadd($valorExistenteDecimal, $valorModificadoDecimal, 2);
                    $filaPymepyc->nuevo_saldo = number_format($nuevoSaldoDecimal, 2, '.', ',');
                }
            }
        }

        return $datos;
    }

    private function informepycnube($nit, $fecha, $datos2)
    {
        $datos = Clientes::selectRaw('
            LEFT(clientes.codigo_cuenta_contable_ga, 4) as cuenta
        ')
        ->selectRaw('
            MAX(clientes.nombre_cuenta_contable_ga) as nombre_orden_informes
        ')
        ->selectRaw('
            ROUND(SUM(clientes.saldo_inicial_ga), 0) as saldoinicial
        ')
        ->selectRaw('
            ROUND(SUM(clientes.movimiento_debito_ga), 0) as debitos
        ')
        ->selectRaw('
            ROUND(SUM(clientes.movimiento_credito_ga), 0) as creditos
        ')
        ->selectRaw('
            ROUND(SUM(clientes.saldo_final_ga), 0) as saldo_mov
        ')
        ->selectRaw('
            ROUND(
                CASE 
                    WHEN LEFT(clientes.codigo_cuenta_contable_ga, 1) = "1" 
                    THEN SUM(IFNULL(clientes.saldo_final_ga, 0))
                    ELSE SUM(IFNULL(clientes.saldo_final_ga, 0))
                END, 2
            ) AS total_mes
        ')
        ->where('clientes.Nit', $nit)
        ->whereYear('clientes.fechareporte_ga', '=', date('Y', strtotime($fecha)))
        ->whereMonth('clientes.fechareporte_ga', '=', date('m', strtotime($fecha)))
        ->whereRaw('LENGTH(clientes.codigo_cuenta_contable_ga) <= 4') // Solo cuentas de hasta 4 dígitos
        ->groupByRaw('LEFT(clientes.codigo_cuenta_contable_ga, 4)')
        ->orderBy('cuenta', 'ASC')
        ->get();

        return $datos;
       
    }

}