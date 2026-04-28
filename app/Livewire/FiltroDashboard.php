<?php

namespace App\Livewire;

use App\Models\Actividad;
use App\Models\ActividadCliente;
use App\Models\Empresa;
use App\Models\EstadoActividad;
use App\Models\EstadoRequerimiento;
use App\Models\ReporteActividad;
use App\Models\SeguimientoRequerimiento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FiltroDashboard extends Component
{
    public $empresas;
    public $responsables;
    public $requerimientos;
    public $tipoActividades;
    public $estadoActividades;
    public $programados;
    public $enproceso;
    public $realizados;
    public $finalizados;
    public $cumplidos;
    public $vencidos;

    public $responsable = null;
    public $empresa = null;
    public $fechaInicio = null;
    public $fechaFin = null;
    public $fecha_min = "";




    public function filtrarActividades()
    {
        if (isset($this->empresa) || isset($this->responsable) || (isset($this->fechaInicio) && isset($this->fechaFin))) {

            $partsEmp = explode('-', $this->empresa);
            $empresa = trim($partsEmp[0]);

            $partsResp = explode('-', $this->responsable);
            $responsable = trim($partsResp[0]);

            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
                $estado_requerimientos = EstadoRequerimiento::select('id')->whereNotIn('id', [1])->get();

                $requerimientos = [];

                foreach ($estado_requerimientos as $estado) {

                    if ($this->fechaInicio && $this->fechaFin) {
                        $contador = SeguimientoRequerimiento::whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])->where('estado_requerimiento_id', $estado->id);
                    } else {
                        $contador = SeguimientoRequerimiento::where('estado_requerimiento_id', $estado->id);
                    }

                    if ($this->empresa && $this->responsable) {
                        $contador = $contador->where('empresa_id', $empresa)
                            ->where('user_id', $responsable)
                            ->count();
                    } else if ($this->empresa) {
                        $contador = $contador->where('empresa_id', $empresa)
                            ->count();
                    } else if ($this->responsable) {
                        $contador = $contador->where('user_id', $responsable)
                            ->count();
                    } else {
                        $contador = $contador->count();
                    }

                    $requerimientos[] =  $contador;
                }

                //Requerimientos por estado
                $this->requerimientos = json_encode($requerimientos);


                $tipo_actividades = Actividad::all();
                $tipoActividades = [];

                foreach ($tipo_actividades as $tp) {

                    if ($this->fechaInicio && $this->fechaFin) {
                        $contador = ActividadCliente::whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])->where('actividad_id', $tp->id);
                    } else {
                        $contador = ActividadCliente::where('actividad_id', $tp->id);
                    }

                    if ($this->empresa && $this->responsable) {
                        $contador = $contador->where(function ($query) use ($empresa) {
                            $query->where('empresa_asociada_id', $empresa)
                                ->orWhere('cliente_id', $this->empresa);
                        })
                            ->where('usuario_id', $responsable)
                            ->count();
                    } else if ($this->empresa) {
                        $contador = $contador->where(function ($query) use ($empresa) {
                            $query->where('empresa_asociada_id', $empresa)
                                ->orWhere('cliente_id', $this->empresa);
                        })
                            ->count();
                    } else if ($this->responsable) {
                        $contador = $contador->where('usuario_id', $responsable)
                            ->count();
                    } else {
                        $contador = $contador->count();
                    }

                    $tipoActividades[] = ['label' => $tp->nombre, 'value' => $contador];
                }

                //Actividades por tipo de actividad
                $this->tipoActividades = json_encode($tipoActividades);

                $estados = EstadoActividad::whereNotIn('id', [5])->get();
                $estado_actividades = [];

                foreach ($estados as $estado) {

                    if ($this->fechaInicio && $this->fechaFin) {
                        $contador = ActividadCliente::with('reporte_actividades')->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])
                            ->whereHas('reporte_actividades', function ($query) use ($estado) {
                                $query->where('estado_actividad_id', $estado->id);
                            });
                    } else {
                        $contador = ActividadCliente::with('reporte_actividades')->whereHas('reporte_actividades', function ($query) use ($estado) {
                            $query->where('estado_actividad_id', $estado->id);
                        });
                    }

                    if ($this->empresa && $this->responsable) {
                        $contador = $contador->where(function ($query) use ($empresa) {
                            $query->where('empresa_asociada_id', $empresa)
                                ->orWhere('cliente_id', $this->empresa);
                        })
                            ->where('usuario_id', $responsable)
                            ->count();
                    } else if ($this->empresa) {
                        $contador = $contador->where(function ($query) use ($empresa) {
                            $query->where('empresa_asociada_id', $empresa)
                                ->orWhere('cliente_id', $this->empresa);
                        })
                            ->count();
                    } else if ($this->responsable) {
                        $contador = $contador->where('usuario_id', $responsable)
                            ->count();
                    } else {
                        $contador = $contador->count();
                    }

                    $estado_actividades[] = ['value' => $contador, 'color' => '#0000'];
                }

                //Actividades por estado
                $this->estadoActividades = json_encode($estado_actividades);


                $this->programados = ActividadCliente::where(function ($query) {
                    $query->whereHas('reporte_actividades', function ($query) {
                        $query->where('estado_actividad_id', 1); //Consulta las actividades programadas
                    });
                });

                $this->enproceso = ActividadCliente::where(function ($query) {
                    $query->whereHas('reporte_actividades', function ($query) {
                        $query->where('estado_actividad_id', 2); //Consulta las actividades en proceso
                    });
                });

                $this->realizados = ActividadCliente::where(function ($query) {
                    $query->whereHas('reporte_actividades', function ($query) {
                        $query->where('estado_actividad_id', 7);
                        $query->OrWhere('estado_actividad_id', 8); //Consulta las actividades cumplidas o finalizadas
                    });
                });

                $this->finalizados = ActividadCliente::where(function ($query) {
                    $query->whereHas('reporte_actividades', function ($query) {
                        $query->where('estado_actividad_id', 7); //consulta actividades finalizadas
                    });
                });

                $this->cumplidos = ActividadCliente::where(function ($query) {
                    $query->whereHas('reporte_actividades', function ($query) {
                        $query->where('estado_actividad_id', 8); //consulta actividades cumplidos
                    });
                });

                $this->vencidos = ActividadCliente::where(function ($query) {
                    $query->whereHas('reporte_actividades', function ($query) {
                        $query->where('estado_actividad_id', 6); //consulta actividades cumplidos
                    });
                });

                if ($this->fechaInicio && $this->fechaFin) {
                    $this->programados = $this->programados->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin]);
                    $this->enproceso = $this->enproceso->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin]);
                    $this->realizados = $this->realizados->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin]);
                    $this->finalizados = $this->finalizados->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin]);
                    $this->cumplidos = $this->cumplidos->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin]);
                    $this->vencidos = $this->vencidos->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin]);
                }

                if ($this->empresa && $this->responsable) { //por empresa y responsable
                    $this->programados = $this->programados->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->where('usuario_id', $responsable)
                        ->count();

                    $this->enproceso = $this->enproceso->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->where('usuario_id', $responsable)
                        ->count();

                    $this->realizados = $this->realizados->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->where('usuario_id', $responsable)
                        ->count();

                    $this->finalizados = $this->finalizados->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->where('usuario_id', $responsable)
                        ->count();

                    $this->cumplidos = $this->cumplidos->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->where('usuario_id', $responsable)
                        ->count();

                    $this->vencidos = $this->vencidos->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->where('usuario_id', $responsable)
                        ->count();
                } else if ($this->empresa) { //por empresa
                    $this->programados = $this->programados->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->count();

                    $this->enproceso = $this->enproceso->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->count();

                    $this->realizados = $this->realizados->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->count();

                    $this->finalizados = $this->finalizados->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->count();

                    $this->cumplidos = $this->cumplidos->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->count();

                    $this->vencidos = $this->vencidos->where(function ($query) use ($empresa) {
                        $query->where('empresa_asociada_id', $empresa)
                            ->orWhere('cliente_id', $this->empresa);
                    })
                        ->count();
                } else if ($this->responsable) { //por responsable
                    $this->programados = $this->programados->where('usuario_id', $responsable)
                        ->count();

                    $this->enproceso = $this->enproceso->where('usuario_id', $responsable)->count();

                    $this->realizados = $this->realizados->where('usuario_id', $responsable)
                        ->count();

                    $this->finalizados = $this->finalizados->where('usuario_id', $responsable)
                        ->count();

                    $this->cumplidos = $this->cumplidos->where('usuario_id', $responsable)
                        ->count();

                    $this->vencidos = $this->vencidos->where('usuario_id', $responsable)
                        ->count();
                } else if ($this->fechaInicio && $this->fechaFin) {
                    $this->programados = $this->programados->count();
                    $this->enproceso = $this->enproceso->count();
                    $this->realizados = $this->realizados->count();
                    $this->finalizados = $this->finalizados->count();
                    $this->cumplidos = $this->cumplidos->count();
                    $this->vencidos = $this->vencidos->count();
                }
            } else {

                $estado_requerimientos = EstadoRequerimiento::select('id')->whereNotIn('id', [1])->get();

                $requerimientos = [];

                foreach ($estado_requerimientos as $estado) {

                    $contador = SeguimientoRequerimiento::where('estado_requerimiento_id', $estado->id)
                        ->where('user_id', Auth::user()->id)
                        ->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])
                        ->count();

                    $requerimientos[] =  $contador;
                }

                //Requerimientos por estado
                $this->requerimientos = json_encode($requerimientos);

                $tipo_actividades = Actividad::all();
                $tipoActividades = [];

                foreach ($tipo_actividades as $tp) {
                    $contador = ActividadCliente::where('actividad_id', $tp->id)
                        ->where('usuario_id', Auth::user()->id)
                        ->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])
                        ->count();

                    $tipoActividades[] = ['label' => $tp->nombre, 'value' => $contador];
                }

                //Actividades por tipo de actividad
                $this->tipoActividades = json_encode($tipoActividades);

                $estados = EstadoActividad::whereNotIn('id', [5])->get();
                $estado_actividades = [];

                foreach ($estados as $estado) {
                    $contador = ActividadCliente::whereHas('reporte_actividades', function ($query) use ($estado) {
                        $query->where('estado_actividad_id', $estado->id);
                    })
                        ->where('usuario_id', Auth::user()->id)
                        ->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])
                        ->count();

                    $estado_actividades[] = ['value' => $contador, 'color' => '#0000'];

                    //Actividades por estado
                    $this->estadoActividades = json_encode($estado_actividades);

                    $this->programados = ActividadCliente::where(function ($query) {
                        $query->whereHas('reporte_actividades', function ($query) {
                            $query->where('estado_actividad_id', 1); //consulta actividades programadas
                        });
                    })
                        ->where('usuario_id', Auth::user()->id)
                        ->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])->count();

                    $this->enproceso = ActividadCliente::where(function ($query) {
                        $query->whereHas('reporte_actividades', function ($query) {
                            $query->where('estado_actividad_id', 2); //consulta actividades enproceso
                        });
                    })
                        ->where('usuario_id', Auth::user()->id)
                        ->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])->count();

                    $this->realizados = ActividadCliente::where(function ($query) {
                        $query->whereHas('reporte_actividades', function ($query) {
                            $query->where('estado_actividad_id', 7);
                            $query->OrWhere('estado_actividad_id', 8); //Consulta las actividades cumplidas o finalizadas
                        });
                    })
                        ->where('usuario_id', Auth::user()->id)
                        ->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])
                        ->count();


                    $this->vencidos = ActividadCliente::where(function ($query) {
                        $query->whereHas('reporte_actividades', function ($query) {
                            $query->where('estado_actividad_id', 6); //consulta actividades vencidas
                        });
                    })
                        ->where('usuario_id', Auth::user()->id)
                        ->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])->count();

                    $this->finalizados = ActividadCliente::where(function ($query) {
                        $query->whereHas('reporte_actividades', function ($query) {
                            $query->where('estado_actividad_id', 7); //consulta actividades finalizadas
                        });
                    })
                        ->where('usuario_id', Auth::user()->id)
                        ->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])->count();

                    $this->cumplidos = ActividadCliente::where(function ($query) {
                        $query->whereHas('reporte_actividades', function ($query) {
                            $query->where('estado_actividad_id', 8); //consulta actividades cumplidos
                        });
                    })
                        ->where('usuario_id', Auth::user()->id)
                        ->whereBetween('fecha_vencimiento', [$this->fechaInicio, $this->fechaFin])->count();
                }
            }

            $this->dispatch('recargarGraficos',  [
                'requerimientos' => $this->requerimientos,
                'tipoActividades' => $this->tipoActividades,
                'estadoActividades' => $this->estadoActividades
            ]);
        } else {

            $this->dispatch('recargarGraficos',  [
                'requerimientos' => $this->requerimientos,
                'tipoActividades' => $this->tipoActividades,
                'estadoActividades' => $this->estadoActividades
            ]);
        }
    }

    public function quitarFiltro()
    {
        $this->empresa = null;
        $this->responsable = null;
        $this->fechaInicio = "";
        $this->fechaFin = "";

        $this->empresas = Empresa::select('id', 'razon_social')->where('estado', 1)->orderBy('id')->get();
        $this->responsables = User::select('id', 'nombres', 'apellidos')->orderBy('nombres')->where('estado', 'Activo')->get();

        $estado_requerimientos = EstadoRequerimiento::select('id')->whereNotIn('id', [1])->get();
        $requerimientos = [];

        foreach ($estado_requerimientos as $estado) {
            $contador = SeguimientoRequerimiento::where('estado_requerimiento_id', $estado->id);

            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
                $contador = $contador->count();
            } else {
                $contador = $contador->where('user_id', Auth::user()->id)->count();
            }

            $requerimientos[] =  $contador;
        }

        $this->requerimientos = json_encode($requerimientos);

        $tipo_actividades = Actividad::all();
        $tipoActividades = [];

        foreach ($tipo_actividades as $tp) {

            $contador = ActividadCliente::where('actividad_id', $tp->id);

            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
                $contador = $contador->count();
            } else {
                $contador = $contador->where('usuario_id', Auth::user()->id)->count();
            }

            $tipoActividades[] = ['label' => $tp->nombre, 'value' => $contador];
        }

        $this->tipoActividades = json_encode($tipoActividades);

        $estados = EstadoActividad::whereNotIn('id', [5])->get();
        $estado_actividades = [];

        foreach ($estados as $estado) {

            $contador = ActividadCliente::whereHas('reporte_actividades', function ($query) use ($estado) {
                $query->where('estado_actividad_id', $estado->id);
            });

            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
                $contador = $contador->count();
            } else {
                $contador = $contador->where('usuario_id', Auth::user()->id)->count();
            }

            $estado_actividades[] = ['value' => $contador, 'color' => '#000000ff'];
        }

        $this->estadoActividades = json_encode($estado_actividades);

        $this->programados = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 1); //consulta actividades programadas
            });
        });

        $this->enproceso = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 2); //consulta actividades en proceso
            });
        });

        $this->realizados = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 7);
                $query->OrWhere('estado_actividad_id', 8); //Consulta las actividades cumplidas o finalizadas
            });
        });

        $this->finalizados = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 7); //consulta actividades finalizadas
            });
        });

        $this->cumplidos = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 8); //consulta actividades cumplidos
            });
        });

        $this->vencidos = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 6); //consulta actividades cumplidos
            });
        });

        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) { //superadministrador
            $this->programados = $this->programados->count();
            $this->enproceso = $this->enproceso->count();
            $this->realizados =  $this->realizados->count();
            $this->finalizados = $this->finalizados->count();
            $this->cumplidos =  $this->cumplidos->count();
            $this->vencidos =  $this->vencidos->count();
        } else {
            $this->programados = $this->programados->where('usuario_id', Auth::user()->id)->count();
            $this->enproceso = $this->enproceso->where('usuario_id', Auth::user()->id)->count();
            $this->realizados = $this->realizados->where('usuario_id', Auth::user()->id)->count();
            $this->finalizados = $this->finalizados->where('usuario_id', Auth::user()->id)->count();
            $this->cumplidos =  $this->cumplidos->where('usuario_id', Auth::user()->id)->count();
            $this->vencidos = $this->vencidos->where('usuario_id', Auth::user()->id)->count();
        }


        $this->dispatch('recargarGraficos',  [
            'requerimientos' => $this->requerimientos,
            'tipoActividades' => $this->tipoActividades,
            'estadoActividades' => $this->estadoActividades
        ]);

        return view('livewire.filtro-dashboard');
    }


    public function mount()
    {
        $this->empresas = Empresa::select('id', 'razon_social')->where('estado', 1)->orderBy('id')->get();
        $this->responsables = User::select('id', 'nombres', 'apellidos')->orderBy('nombres')->where('estado', 'Activo')->get();

        $estado_requerimientos = EstadoRequerimiento::select('id')->whereNotIn('id', [1])->get();
        $requerimientos = [];

        foreach ($estado_requerimientos as $estado) {
            $contador = SeguimientoRequerimiento::where('estado_requerimiento_id', $estado->id);

            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
                $contador = $contador->count();
            } else {
                $contador = $contador->where('user_id', Auth::user()->id)->count();
            }

            $requerimientos[] =  $contador;
        }

        $this->requerimientos = json_encode($requerimientos);

        $tipo_actividades = Actividad::all();
        $tipoActividades = [];

        foreach ($tipo_actividades as $tp) {

            $contador = ActividadCliente::where('actividad_id', $tp->id);

            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
                $contador = $contador->count();
            } else {
                $contador = $contador->where('usuario_id', Auth::user()->id)->count();
            }

            $tipoActividades[] = ['label' => $tp->nombre, 'value' => $contador];
        }

        $this->tipoActividades = json_encode($tipoActividades);

        $estados = EstadoActividad::whereNotIn('id', [5])->get();
        $estado_actividades = [];

        foreach ($estados as $estado) {

            $contador = ActividadCliente::whereHas('reporte_actividades', function ($query) use ($estado) {
                $query->where('estado_actividad_id', $estado->id);
            });

            if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
                $contador = $contador->count();
            } else {
                $contador = $contador->where('usuario_id', Auth::user()->id)->count();
            }

            $estado_actividades[] = ['value' => $contador, 'color' => '#0000'];
        }

        $this->estadoActividades = json_encode($estado_actividades);

        $this->programados = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 1); //consulta actividades programadas
            });
        });

        $this->enproceso = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 2); //consulta actividades en proceso
            });
        });

        $this->realizados = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 7);
                $query->OrWhere('estado_actividad_id', 8); //Consulta las actividades cumplidas o finalizadas
            });
        });

        $this->finalizados = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 7); //consulta actividades finalizadas
            });
        });

        $this->cumplidos = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 8); //consulta actividades cumplidos
            });
        });

        $this->vencidos = ActividadCliente::where(function ($query) {
            $query->whereHas('reporte_actividades', function ($query) {
                $query->where('estado_actividad_id', 6); //consulta actividades cumplidos
            });
        });

        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) { //superadministrador
            $this->programados = $this->programados->count();
            $this->enproceso = $this->enproceso->count();
            $this->realizados =  $this->realizados->count();
            $this->finalizados = $this->finalizados->count();
            $this->cumplidos =  $this->cumplidos->count();
            $this->vencidos =  $this->vencidos->count();
        } else {
            $this->programados = $this->programados->where('usuario_id', Auth::user()->id)->count();
            $this->enproceso = $this->enproceso->where('usuario_id', Auth::user()->id)->count();
            $this->realizados = $this->realizados->where('usuario_id', Auth::user()->id)->count();
            $this->finalizados = $this->finalizados->where('usuario_id', Auth::user()->id)->count();
            $this->cumplidos =  $this->cumplidos->where('usuario_id', Auth::user()->id)->count();
            $this->vencidos = $this->vencidos->where('usuario_id', Auth::user()->id)->count();
        }
    }
}
