<?php

namespace App\Livewire;

use App\Models\ActividadCliente;
use App\Models\Empresa;
use App\Models\EstadoActividad;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FiltroActividades extends Component
{
    public $actividades;
    public $empresas;
    public $estados;
    public $responsables;

    //selecionado
    public $nombreActividad = null;
    public $idActividad = null;
    public $responsable = null;
    public $estado = null;
    public $empresa = null;
    public $fechaInicioVencimiento = null;
    public $fechaFinVencimiento = null;
    public $fecha_min = "";

    public $filtroAplicado = "";

    public function filtrarTabla()
    {

        //Se filtra solo por id
        //Filtra por fechas
        //Filtra por nombre y empresa
        //Filtra por empresa y responsable de la actividad
        //Filtra por empresa y la fecha de vencimiento
        //Filtra por nombre de la actividad y la fecha de vencimiento
        //Filtra por nombre de actividad, empresa y fecha de vencimiento
        //Filtra por estado y empresa
        //Filtra por estado y responsable
        //Filtra por empresa, responsable y estado


        //administrador
        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2){

            if ($this->idActividad) {
                $this->actividades = ActividadCliente::where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->where('id', $this->idActividad)
                    ->get();
            }

            if ($this->nombreActividad) {
                $this->actividades = ActividadCliente::query()->when($this->nombreActividad, function ($query) {
                    $query->where('nombre', 'like', $this->nombreActividad . '%');
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) { //Si la empresa asociada es null busca por cliente_id
                        $query->where('estado', 1);
                    });
                })
                    ->get();
            }

            if ($this->estado) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->get();
            }

            if ($this->empresa) {
                $this->actividades = ActividadCliente::where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })
                ->get();
            }

            if ($this->responsable) {
                $this->actividades = ActividadCliente::where('usuario_id', $this->responsable)->get();
            }

            if ($this->fechaInicioVencimiento && $this->fechaFinVencimiento) {
                $this->actividades = ActividadCliente::whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])
                    ->where(function ($query) {
                        $query->whereHas('empresa_asociada', function ($query) {
                            $query->where('estado', 1);
                        })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                            $query->where('estado', 1);
                        });
                    })
                    ->get();
            }

            if ($this->nombreActividad && $this->empresa) {
                $this->actividades = ActividadCliente::query()->when($this->nombreActividad, function ($query) {
                    $query->where('nombre', 'like', $this->nombreActividad . '%');
                })->where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })
                    ->get();
            }

            if ($this->empresa && $this->responsable) {
                $this->actividades = ActividadCliente::where('usuario_id', $this->responsable)->where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })
                    ->get();
            }

            if ($this->empresa && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })
                    ->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])
                    ->get();
            }

            if ($this->nombreActividad && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::query()->when($this->nombreActividad, function ($query) {
                    $query->where('nombre', 'like', $this->nombreActividad . '%');
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })
                    ->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])
                    ->get();
            }

            if ($this->nombreActividad && $this->empresa && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::query()->when($this->nombreActividad, function ($query) {
                    $query->where('nombre', 'like', $this->nombreActividad . '%');
                })->where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })
                    ->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])
                    ->get();
            }

            if ($this->estado && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })
                    ->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])
                    ->get();
            }

            if ($this->estado && $this->empresa) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->get();
            }

            if ($this->estado && $this->responsable) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where('usuario_id', $this->responsable)->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->get();
            }

            if ($this->empresa && $this->responsable && $this->estado) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where('usuario_id', $this->responsable)
                    ->where(function ($query) {
                        $query->whereHas('empresa_asociada', function ($query) {
                            $query->where('estado', 1);
                        })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                            $query->where('estado', 1);
                        });
                    })->get();
            }

            if ($this->estado && $this->responsable && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where('usuario_id', $this->responsable)->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })
                ->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])->get();
            }
            
            if ($this->estado && $this->empresa && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])->get();
            }

            if ($this->empresa && $this->responsable && $this->estado && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where('usuario_id', $this->responsable)
                    ->where(function ($query) {
                        $query->whereHas('empresa_asociada', function ($query) {
                            $query->where('estado', 1);
                        })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                            $query->where('estado', 1);
                        });
                    })->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])->get();
            }

            //Otros usuarios
        } else {

            if ($this->idActividad) {
                $this->actividades = ActividadCliente::where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->where('id', $this->idActividad)
                    ->where(function ($query) {
                        $query->where('usuario_id', Auth::user()->id)
                            ->orWhere('user_crea_act_id', Auth::user()->id);
                    })
                    ->get();
            }

            if ($this->nombreActividad) {
                $this->actividades = ActividadCliente::query()->when($this->nombreActividad, function ($query) {
                    $query->where('nombre', 'like', $this->nombreActividad . '%');
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })->get();
            }

            if ($this->estado) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })
                    ->where(function ($query) {
                        $query->whereHas('empresa_asociada', function ($query) {
                            $query->where('estado', 1);
                        })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                            $query->where('estado', 1);
                        });
                    })->get();
            }

            if ($this->empresa) {
                $this->actividades = ActividadCliente::where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })->get();
            }


            if ($this->fechaInicioVencimiento && $this->fechaFinVencimiento) {
                $this->actividades = ActividadCliente::whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])
                    ->where(function ($query) {
                        $query->whereHas('empresa_asociada', function ($query) {
                            $query->where('estado', 1);
                        })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                            $query->where('estado', 1);
                        });
                    })->where(function ($query) {
                        $query->where('usuario_id', Auth::user()->id)
                            ->orWhere('user_crea_act_id', Auth::user()->id);
                    })
                    ->get();
            }


            if ($this->nombreActividad && $this->empresa) {
                $this->actividades = ActividadCliente::query()->when($this->nombreActividad, function ($query) {
                    $query->where('nombre', 'like', $this->nombreActividad . '%');
                })->where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })
                    ->get();
            }

            if ($this->empresa && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })
                    ->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])
                    ->get();
            }

            if ($this->nombreActividad && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::query()->when($this->nombreActividad, function ($query) {
                    $query->where('nombre', 'like', $this->nombreActividad . '%');
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })
                    ->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])
                    ->get();
            }

            if ($this->nombreActividad && $this->empresa && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::query()->when($this->nombreActividad, function ($query) {
                    $query->where('nombre', 'like', $this->nombreActividad . '%');
                })->where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })
                    ->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])
                    ->get();
            }

            if ($this->estado && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })
                    ->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])
                    ->get();
            }


            if ($this->estado && $this->empresa) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })
                    ->get();
            }

            if ($this->estado && $this->empresa && ($this->fechaInicioVencimiento && $this->fechaFinVencimiento)) {
                $this->actividades = ActividadCliente::whereHas('reporte_actividades', function ($query) {
                    $query->where('estado_actividad_id', $this->estado);
                })->where(function ($query) {
                    $query->where('empresa_asociada_id', $this->empresa)
                        ->orWhere('cliente_id', $this->empresa);
                })->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })->whereBetween('fecha_vencimiento', [$this->fechaInicioVencimiento, $this->fechaFinVencimiento])->get();
            }
        }

        $this->dispatch('recargarTabla');
        $this->mensajeFiltroAplicado();
    }

    private function mensajeFiltroAplicado()
    {
        $nombre = $this->nombreActividad ? $this->nombreActividad : "";
        $id =  $this->idActividad ? '#' . $this->idActividad : "";
        $empresa = null;
        $estado = null;
        $responsable  = null;
        $fecha_inicio_vencimiento =  "";
        $fecha_fin_vencimiento =  "";

        if ($this->fechaInicioVencimiento && $this->fechaFinVencimiento) {
            $fecha_inicio_vencimiento = Carbon::parse($this->fechaInicioVencimiento)->format('d-m-Y');
            $fecha_fin_vencimiento = Carbon::parse($this->fechaFinVencimiento)->format('d-m-Y');
        }

        if ($this->empresa) {
            $findEmpresa = Empresa::where('id', $this->empresa)->first();
            $empresa = $findEmpresa->razon_social;
        }

        if ($this->estado) {
            $findEstado = EstadoActividad::where('id', $this->estado)->first();
            $estado = $findEstado->nombre;
        }

        if ($this->responsable) {
            $findResponsable = User::where('id', $this->responsable)->first();
            $responsable = $findResponsable->nombres . ' ' . $findResponsable->apellidos;
        }

        if (count($this->actividades)) {
            $this->empresa = null;
            $this->estado = null;
            $this->responsable = null;
            $this->nombreActividad = null;
            $this->fechaInicioVencimiento = "";
            $this->fechaFinVencimiento = "";
            $this->idActividad = "";

            $this->filtroAplicado = "<h2 class='badge px-3 py-2  fw-normal bg-light text-dark'>" . $nombre
                . "</h2><h2 class='badge px-3 py-2  fw-normal bg-light text-dark'>" . $id
                . "</h2><h2 class='badge px-3 py-2  fw-normal bg-light text-dark'>" . $estado
                . "</h2><h2 class='badge px-3 py-2  fw-normal bg-light text-dark'>" . $empresa
                . "</h2><h2 class='badge px-3 py-2  fw-normal bg-light text-dark'>" . $responsable
                . "</h2><h2 class='badge px-3 py-2  fw-normal bg-light text-dark'>" . $fecha_inicio_vencimiento
                . "</h2><h2 class='badge px-3 py-2  fw-normal bg-light text-dark'>" . $fecha_fin_vencimiento
                . "</h2>";
        } else {
            $this->filtroAplicado = "<h2 class='badge px-3 py-2 fw-normal text-info'> <i class='fas fa-info-circle'></i> No se encontraron resultados. </h2>";
        }
    }

    public function quitarFiltro()
    {
        $this->empresa = null;
        $this->nombreActividad = null;
        $this->estado = null;
        $this->responsable = null;
        $this->filtroAplicado = "";
        $this->fechaInicioVencimiento = "";
        $this->fechaFinVencimiento = "";
        $this->idActividad = "";

        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2){
            $this->actividades =  ActividadCliente::with(
                'reporte_actividades',
                'cliente',
                'empresa_asociada',
                'estado_actividades',
                'reporte_actividades.estado_actividades'
            )->where(function ($query) {
                $query->whereHas('empresa_asociada', function ($query) {
                    $query->where('estado', 1);
                })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                    $query->where('estado', 1);
                });
            })
                ->select('actividad_cliente.*')
                ->get();
        } else {
            $this->actividades =  ActividadCliente::with(
                'reporte_actividades',
                'cliente',
                'empresa_asociada',
                'estado_actividades',
                'reporte_actividades.estado_actividades'
            )->where(function ($query) {
                $query->whereHas('empresa_asociada', function ($query) {
                    $query->where('estado', 1);
                })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                    $query->where('estado', 1);
                });
            })
                ->select('actividad_cliente.*')
                ->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })
                ->get();
        }

        $this->empresas = Empresa::select('id', 'razon_social')->orderBy('razon_social')->where('estado', 1)->get();
        $this->estados = EstadoActividad::select('id', 'nombre')->get();
        $this->responsables = User::select('id', 'nombres', 'apellidos')->orderBy('nombres')->where('estado', 'Activo')->get();
        $this->dispatch('recargarTabla');

        return view('livewire.filtro-actividades');
    }

    public function mount()
    {
        $this->fecha_min = $this->fechaInicioVencimiento;

        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2){
            $this->actividades =  ActividadCliente::with(
                'reporte_actividades',
                'cliente',
                'empresa_asociada',
                'estado_actividades',
                'reporte_actividades.estado_actividades'
            )
                ->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })
                ->select('actividad_cliente.*')
                ->get();
        } else {
            $this->actividades =  ActividadCliente::with(
                'reporte_actividades',
                'cliente',
                'empresa_asociada',
                'estado_actividades',
                'reporte_actividades.estado_actividades'
            )
                ->where(function ($query) {
                    $query->whereHas('empresa_asociada', function ($query) {
                        $query->where('estado', 1);
                    })->orWhereDoesntHave('empresa_asociada')->whereHas('cliente', function ($query) {
                        $query->where('estado', 1);
                    });
                })
                ->select('actividad_cliente.*')
                ->where(function ($query) {
                    $query->where('usuario_id', Auth::user()->id)
                        ->orWhere('user_crea_act_id', Auth::user()->id);
                })
                ->get();
        }

        $this->empresas = Empresa::select('id', 'razon_social')->orderBy('razon_social')->where('estado', 1)->get();
        $this->estados = EstadoActividad::select('id', 'nombre')->get();
        $this->responsables = User::select('id', 'nombres', 'apellidos')->orderBy('nombres')->where('estado', 'Activo')->get();
    }
}
