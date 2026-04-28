<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\calendario_tributario;
use App\Models\DepartamentosCiudades;
use App\Models\Empresa;
use App\Models\FechasMunicipalesCT;
use App\Models\FechasOtrasEntidadesCT;
use App\Models\FechasPorEmpresaCT;
use Exception;

class FechasCalendarioTributarioController extends Controller
{
    public static function Datos($id): array
    {
       
        try {
            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', 600);
            $empresas = Empresa::find($id);
            if($empresas->estado==1 || $id=="1"){
                if (!empty($empresas->obligaciones)) {
                    $nit = $empresas->NIT;

                    $obligaciones = json_decode($empresas->obligaciones);
                    $calendarios = calendario_tributario::query()
                    ->select('calendario_tributario.fecha_vencimiento', 'obligacionesdian.nombre', 'codigo_tributario', 'detalle_tributario','detalles_tributario.nombre as nombre_detalle')
                    ->join('obligacionesdian', 'obligacionesdian.codigo', '=', 'calendario_tributario.codigo_tributario')
                    ->join('detalles_tributario','detalles_tributario.codigo','=','calendario_tributario.detalle_tributario')
                    ->where(function ($query) use ($obligaciones) {
                        foreach ($obligaciones as $codigo) {
                            $query->orWhere('codigo_tributario', $codigo);
                        }
                    })
                    ->where(function ($query) use ($nit) {
                        $last_digit = substr($nit, -1);
                        $last_two_digits = substr($nit, -2);
                        
                        $query->where(function ($query) use ($last_digit, $last_two_digits) {
                            $query->where('ultimo_digito', 'SI')
                                ->where('rango_final', '>=', $last_digit)
                                ->where('rango_inicial', '<=', $last_digit);
                        })
                        ->orWhere(function ($query) use ($last_two_digits) {
                            $query->where('ultimos_digitos', 'SI')
                                ->where('rango_final', '>=', $last_two_digits)
                                ->where('rango_inicial', '<=', $last_two_digits);
                        });

                        // Verificar si el rango es de 9 a 0
                        if ($last_digit === '9' || $last_digit === '0') {
                            $query->orWhere(function ($query) {
                                $query->where('rango_inicial', '>', 'rango_final')
                                    ->where('ultimo_digito', 'SI')
                                    ->where('rango_final', '<=', 0)
                                    ->where('rango_inicial', '=', 9);
                            });
                        }
                    })
                    ->get();
                    
                    if (empty($calendarios)) {
                        return ['color' => 'warning', 'mensaje' => 'No se encontraron registros.'];
                    }
                    //mapeo los datos y agrego el nit de la empresa
                    $calendarios = $calendarios->map(function ($item) use ($nit) {
                        return [
                            'fecha_vencimiento'         => $item->{'fecha_vencimiento'},
                            'nombre'                    => $item->{'nombre'},
                            'codigo_tributario'         => $item->{'codigo_tributario'},
                            'NIT'                       => $nit,
                            'fecha_revision'            => $item->{'fecha_revision'},
                            'observacion'               => $item->{'observacion'},
                            'detalle_tributario'        => $item->{'detalle_tributario'},
                            'nombre_detalle'            => $item->{'nombre_detalle'},
                            'fecha_actualizacion'       => now(), // Agrega la fecha y hora actual
                        ];
                    })->toArray();
                    $batchSize = 100;
                    $calendarios = array_chunk($calendarios, $batchSize);

                    $fileContent = "";
                    // Eliminar las obligaciones existentes de la empresa
                    FechasPorEmpresaCT::where('NIT', $nit)->whereNull('fecha_revision')->delete();
                    // Guardar en la tabla maestro terceros
                    foreach ($calendarios as $calendarioChunk) {
                        foreach ($calendarioChunk as $calendarioItem) {
                            FechasPorEmpresaCT::updateOrCreate(
                                [
                                    'NIT' => $calendarioItem['NIT'],
                                    'codigo_tributario'  => $calendarioItem['codigo_tributario'],
                                    'fecha_vencimiento'  => $calendarioItem['fecha_vencimiento'],
                                    'nombre'             => $calendarioItem['nombre'],
                                    'detalle_tributario' => $calendarioItem['detalle_tributario'],
                                    'nombre_detalle'     => $calendarioItem['nombre_detalle'],
                                ]
                            );
                            // Agregar datos al contenido del archivo
                            $fileContent .= implode(',', $calendarioItem) . PHP_EOL;
                        }
                    }
                    // Crear o actualizar el archivo con los datos
                    $filePath = storage_path('app/datos_actualizados.csv');
                    file_put_contents($filePath, $fileContent);
                }
            }else{
                // Eliminar las obligaciones existentes de la empresa
                FechasPorEmpresaCT::where('NIT', $empresas->NIT)->whereNull('fecha_revision')->delete();
            }
        } catch (Exception $e) {
            return ['color' => 'warning', 'mensaje' => 'Error de conexión, mostrando datos de la última consulta realizada.'];
        }
        return ['color' => 'success', 'mensaje' => 'Tabla fechas actualizada.'];
    }

    public static function Datosmunicipales($id): array
    {
       
        try {
            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', 600);
            $empresas = Empresa::find($id);

                if($empresas->estado==1 || $id=="1"){
                    $nit = $empresas->NIT;
                    if(!empty($empresas->codigo_obligacionmunicipal)){
                        $obligacionesMunicipales = json_decode($empresas->codigo_obligacionmunicipal);
                        $pivote = 1;
                    }else{
                        $pivote=2;
                        $obligacionesMunicipales = json_decode(json_decode($empresas->obligacionesmunicipales)[0], true);
                        // Inicializa un arreglo para almacenar las ciudades
                        $municipio = [];
                        // Accede a la propiedad "ciudad"
                        foreach ($obligacionesMunicipales as $item){
                        $municipio = array_merge($municipio, $item['ciudad']);
                        }
                        $municipios=DepartamentosCiudades::select('c_digo_dane_del_municipio')->whereIn('municipio',$municipio)->get();
                        // Inicializa un arreglo para almacenar los códigos de municipio sin puntos
                        $codigosSinPuntos = [];

                        foreach ($municipios as $municipio) {
                            $codigo = str_replace('.', '', $municipio->c_digo_dane_del_municipio);
                            $codigosSinPuntos[] = $codigo;
                        }
                        $obligacionesMunicipales=$codigosSinPuntos;
                        
                    }
        
                    $obligacionesmunicipales = calendario_tributario::select('calendario_tributario.fecha_vencimiento', 'calendario_tributario.codigo_municipio','calendario_tributario.codigo_tributario', 'obligacionmunicipalesdian.nombre','calendario_tributario.detalle_tributario','detalle_tributario','detalles_tributario.nombre as nombre_detalle')
                    ->join('obligacionmunicipalesdian', 'obligacionmunicipalesdian.codigo', '=', 'calendario_tributario.codigo_tributario')
                    ->join('detalles_tributario','detalles_tributario.codigo','=','calendario_tributario.detalle_tributario')
                    ->where(function ($query) use ($obligacionesMunicipales, $pivote) {
                        if ($pivote===1) {
                            // Si la variable es igual a 1, buscar por  campo codigo_tributario
                            foreach ($obligacionesMunicipales as $codigo) {
                                $query->orWhere('calendario_tributario.codigo_tributario', $codigo);
                            }
                        } else {
                            foreach ($obligacionesMunicipales as $codigo) {
                                $query->orWhere('calendario_tributario.codigo_municipio', $codigo);
                            }
                        }
                    })
                    ->where(function ($query) use ($nit) {
                        $last_digit = substr($nit, -1);
                        $last_two_digits = substr($nit, -2);
                        
                        $query->where(function ($query) use ($last_digit, $last_two_digits) {
                            $query->where('calendario_tributario.ultimo_digito', 'SI')
                                ->where('calendario_tributario.rango_final', '>=', $last_digit)
                                ->where('calendario_tributario.rango_inicial', '<=', $last_digit);
                        })
                        ->orWhere(function ($query) use ($last_two_digits) {
                            $query->where('calendario_tributario.ultimos_digitos', 'SI')
                                ->where('calendario_tributario.rango_final', '>=', $last_two_digits)
                                ->where('calendario_tributario.rango_inicial', '<=', $last_two_digits);
                        });

                        // Verificar si el rango es de 9 a 0
                        if ($last_digit === '9' || $last_digit === '0') {
                            $query->orWhere(function ($query) {
                                $query->where('calendario_tributario.rango_inicial', '>', 'calendario_tributario.rango_final')
                                    ->where('calendario_tributario.ultimo_digito', 'SI')
                                    ->where('calendario_tributario.rango_final', '<=', 0)
                                    ->where('calendario_tributario.rango_inicial', '=', 9);
                            });
                        }
                    
                    })
                    ->get();
                    if (empty($obligacionesmunicipales)) {
                        return ['color' => 'warning', 'mensaje' => 'No se encontraron registros.'];
                    }

                    //mapeo los datos y agrego el nit de la empresa
                    $obligaciones = $obligacionesmunicipales->map(function ($item) use ($nit) {
                        return [
                            'fecha_vencimiento'         => $item->{'fecha_vencimiento'},
                            'nombre'                    => $item->{'nombre'},
                            'codigo_municipio'          => $item->{'codigo_municipio'},
                            'codigo_tributario'         => $item->{'codigo_tributario'},
                            'detalle_tributario'        => $item->{'detalle_tributario'},
                            'nombre_detalle'            => $item->{'nombre_detalle'},
                            'NIT'                       => $nit,
                            'fecha_revision'            => $item->{'fecha_revision'},
                            'observacion'               => $item->{'observacion'},
                            'fecha_actualizacion'       => now(), // Agrega la fecha y hora actual
                        ];
                    })->toArray();
                    $batchSize = 100;
                    $obligaciones = array_chunk($obligaciones, $batchSize);
                    $fileContent = "";
                    // Eliminar las obligaciones existentes de la empresa
                    FechasMunicipalesCT::where('NIT', $nit)->whereNull('fecha_revision')->delete();
                    // Guardar en la tabla maestro terceros
                    foreach ($obligaciones as $obligacionesChunk) {
                        foreach ($obligacionesChunk as $obligacionesItem) {
                            FechasMunicipalesCT::updateOrCreate(
                                [
                                    'NIT' => $obligacionesItem['NIT'],
                                    'codigo_municipio'    => $obligacionesItem['codigo_municipio'],
                                    'codigo_tributario'   => $obligacionesItem['codigo_tributario'],
                                    'detalle_tributario'  => $obligacionesItem['detalle_tributario'],
                                    'nombre_detalle'      => $obligacionesItem['nombre_detalle'],
                                    'fecha_vencimiento'   => $obligacionesItem['fecha_vencimiento'],
                                    'nombre'              => $obligacionesItem['nombre'],
                                ]
                            );
                            // Agregar datos al contenido del archivo
                            $fileContent .= implode(',', $obligacionesItem) . PHP_EOL;
                        }
                    }
                    // Crear o actualizar el archivo con los datos
                    $filePath = storage_path('app/datos_municipales_actualizados.csv');
                    file_put_contents($filePath, $fileContent);
                }else{
                    // Eliminar las obligaciones existentes de la empresa
                    FechasMunicipalesCT::where('NIT', $empresas->NIT)->whereNull('fecha_revision')->delete();
                }
            
            
        } catch (Exception $e) {
            return ['color' => 'warning', 'mensaje' => 'Error de conexión, mostrando datos de la última consulta realizada.'];
        }
        return ['color' => 'success', 'mensaje' => 'Tabla fechas actualizada.'];
    }

    public static function Datosotrasentidades($id): array
    {
       
        try {
            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', 600);
            $empresas = Empresa::find($id);
            if($empresas->estado==1 || $id=="1"){
                if(!empty($empresas->otras_entidades)){
                    $nit = $empresas->NIT;
                    $otras_entidades = json_decode($empresas->otras_entidades);
                    
        
                    $obligacionesotrasentidades = calendario_tributario::select('calendario_tributario.fecha_vencimiento', 'otras_entidadesct.codigo as codigo_otraentidad', 'calendario_tributario.codigo_tributario', 'otras_entidadesct.nombre','detalle_tributario','detalles_tributario.nombre as nombre_detalle')
                    ->join('otras_entidadesct', 'otras_entidadesct.codigo', '=', 'calendario_tributario.codigo_tributario')
                    ->join('detalles_tributario','detalles_tributario.codigo','=','calendario_tributario.detalle_tributario')
                    ->where(function ($query) use ($otras_entidades) {
                        foreach ($otras_entidades as $codigo) {
                            $query->orWhere('calendario_tributario.codigo_tributario', $codigo);
                        }
                    })
                    ->where(function ($query) use ($nit) {
                        $last_digit = substr($nit, -1);
                        $last_two_digits = substr($nit, -2);
                        
                        $query->where(function ($query) use ($last_digit, $last_two_digits) {
                            $query->where('calendario_tributario.ultimo_digito', 'SI')
                                ->where('calendario_tributario.rango_final', '>=', $last_digit)
                                ->where('calendario_tributario.rango_inicial', '<=', $last_digit);
                        })
                        ->orWhere(function ($query) use ($last_two_digits) {
                            $query->where('calendario_tributario.ultimos_digitos', 'SI')
                                ->where('calendario_tributario.rango_final', '>=', $last_two_digits)
                                ->where('calendario_tributario.rango_inicial', '<=', $last_two_digits);
                        });

                        // Verificar si el rango es de 9 a 0
                        if ($last_digit === '9' || $last_digit === '0') {
                            $query->orWhere(function ($query) {
                                $query->where('calendario_tributario.rango_inicial', '>', 'calendario_tributario.rango_final')
                                    ->where('calendario_tributario.ultimo_digito', 'SI')
                                    ->where('calendario_tributario.rango_final', '<=', 0)
                                    ->where('calendario_tributario.rango_inicial', '=', 9);
                            });
                        }
                    
                    })
                    ->get();
                    if (empty($obligacionesotrasentidades)) {
                        return ['color' => 'warning', 'mensaje' => 'No se encontraron registros.'];
                    }

                    //mapeo los datos y agrego el nit de la empresa
                    $obligacionesotras = $obligacionesotrasentidades->map(function ($item) use ($nit) {
                        return [
                            'fecha_vencimiento'           => $item->{'fecha_vencimiento'},
                            'nombre'                      => $item->{'nombre'},
                            'codigo_otraentidad'          => $item->{'codigo_otraentidad'},
                            'codigo_tributario'           => $item->{'codigo_tributario'},
                            'detalle_tributario'          => $item->{'detalle_tributario'},
                            'nombre_detalle'              => $item->{'nombre_detalle'},
                            'NIT'                         => $nit,
                            'fecha_revision'              => $item->{'fecha_revision'},
                            'observacion'                 => $item->{'observacion'},
                            'fecha_actualizacion'         => now(), // Agrega la fecha y hora actual
                        ];
                    })->toArray();
                    $batchSize = 100;
                    $obligacioneso = array_chunk($obligacionesotras, $batchSize);
                    $fileContent = "";
                    // Eliminar las obligaciones existentes de la empresa
                    FechasOtrasEntidadesCT::where('NIT', $nit)->whereNull('fecha_revision')->delete();
                    // Guardar en la tabla maestro terceros
                    foreach ($obligacioneso as $obligacionesChunk) {
                        foreach ($obligacionesChunk as $obligacionesItem) {
                            FechasOtrasEntidadesCT::updateOrCreate(
                                [
                                    'NIT' => $obligacionesItem['NIT'],
                                    'codigo_otraentidad' => $obligacionesItem['codigo_otraentidad'],
                                    'codigo_tributario'  => $obligacionesItem['codigo_tributario'],
                                    'detalle_tributario' => $obligacionesItem['detalle_tributario'],
                                    'nombre_detalle'     => $obligacionesItem['nombre_detalle'],
                                    'fecha_vencimiento'  => $obligacionesItem['fecha_vencimiento'],
                                    'nombre'             => $obligacionesItem['nombre'],
                                ]
                            );
                            // Agregar datos al contenido del archivo
                            $fileContent .= implode(',', $obligacionesItem) . PHP_EOL;
                        }
                    }
                    // Crear o actualizar el archivo con los datos
                    $filePath = storage_path('app/datos_otras_entidades_actualizados.csv');
                    file_put_contents($filePath, $fileContent);
                }
            }else{
               // Eliminar las obligaciones existentes de la empresa
               FechasOtrasEntidadesCT::where('NIT', $empresas->NIT)->whereNull('fecha_revision')->delete(); 
            }
            
        } catch (Exception $e) {
            return ['color' => 'warning', 'mensaje' => 'Error de conexión, mostrando datos de la última consulta realizada.'];
        }
        return ['color' => 'success', 'mensaje' => 'Tabla fechas actualizada.'];
    }
}
