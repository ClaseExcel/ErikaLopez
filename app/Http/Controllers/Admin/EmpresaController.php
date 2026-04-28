<?php

namespace App\Http\Controllers\admin;

use App\Exports\EmpresasExport;
use App\Http\Controllers\Admin\FechasCalendarioTributarioController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\CreateEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use App\Mail\NotificacionCreacionEmpresa;
use App\Models\CamarasComercio;
use App\Models\Cargo;
use App\Models\Clasificacion;
use App\Models\CodigoCiiu;
use App\Models\DepartamentosCiudades;
use App\Models\EmpleadoCliente;
use App\Models\Empresa;
use App\Models\Frecuencia;
use App\Models\ObligacionDian;
use App\Models\ObligacionesMunicipalesDian;
use App\Models\OtrasEntidadesCT;
use App\Models\User;
use App\Models\UVT;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EmpresaController extends Controller
{

    use ActionButtonTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        abort_if(Gate::denies('GESTIONAR_CLIENTES'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {

            $filter = $request->input('filter', []);

            if ($filter) {
                $empresas = Empresa::select('razon_social', 'id')->with('frecuencia')->select('empresas.*')->where(function ($query) use ($filter) {
                    foreach ($filter as $id) {
                        $query->orWhereJsonContains('empleados', $id); // Filtrar por cada ID en el array
                    }
                })->orderBy('razon_social');
            } else {
                if (Auth::user()->role_id == 1) {
                    $empresas = Empresa::select('razon_social', 'id')->with('frecuencia')->select('empresas.*')->orderBy('razon_social');
                } else {
                    $empresas = Empresa::select('razon_social', 'id')->whereJsonContains('empleados', (string)Auth::user()->id)->with('frecuencia')->select('empresas.*')->orderBy('razon_social');
                }
            }

            return DataTables::of($empresas)
                ->addColumn('actions', function ($empresas) {
                    // Lógica para generar las acciones para cada registro de empresas
                    return $this->getActionButtons('admin.empresas', 'EMPRESAS', $empresas['id']);
                })
                ->addColumn('empleados', function ($empresas) {
                    // Lógica para generar las acciones para cada registro de empleados
                    $empleados = $empresas->empleados ? json_decode($empresas->empleados) : [];
                    $usuariosEmpleados = User::withTrashed()->whereIn('id', $empleados)->select('id', 'nombres', 'apellidos')->get();

                    $result = [];

                    if ($empleados) {
                        foreach ($usuariosEmpleados as $responsable) {
                            $result[] = ["id" => $responsable->id, "name" => $responsable->nombres . ' ' . $responsable->apellidos];
                        }
                    } else {
                        $result[] = ["id" => 0, "name" => '<span class="text-secondary fst-italic"> Sin empleados </span>'];
                    }


                    $json = json_encode($result);
                    return json_decode($json);
                })
                ->rawColumns(['empleados', 'actions']) //para que se muestr el codigo html en la tabla
                ->make(true);
        }

        $empleados = User::select('id', 'nombres', 'apellidos')->where('estado', 'ACTIVO')->get();
        return view('admin.empresas.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_EMPRESAS'), Response::HTTP_UNAUTHORIZED);

        $frecuencias = Frecuencia::pluck('nombre', 'id')->prepend('Selecciona una frecuencia');
        $obligaciones = ObligacionDian::all()->sortBy('codigo');
        $ciiu = CodigoCiiu::all()->sortBy('codigo');
        $departamentos = DepartamentosCiudades::pluck('departamento', 'c_digo_dane_del_departamento');
        $camarascomercio = CamarasComercio::all();
        $empleados = User::orderBy('nombres')->selectRaw('CONCAT(nombres, " ", apellidos) as nombre_completo, id')->whereNotIn('role_id', [7])->pluck('nombre_completo', 'id')->toArray();
        $obligacionesmunicipalescodigo = ObligacionesMunicipalesDian::all()->sortBy('codigo');
        $otrasentidades = OtrasEntidadesCT::all()->sortBy('codigo');
        $companias = Empresa::orderBy('razon_social')->pluck('razon_social', 'nit');
        return view(
            'admin.empresas.create',
            compact('frecuencias', 'obligaciones', 'departamentos', 'ciiu', 'camarascomercio', 'empleados', 'obligacionesmunicipalescodigo', 'otrasentidades','companias'),
            ['empresa' => new Empresa, 'clasificacion' => new Clasificacion]
        );
    }

    public function municipio(Request $request)
    {
        $municipio = DepartamentosCiudades::where('c_digo_dane_del_departamento', $request->departamento)
            ->select('municipio', 'c_digo_dane_del_municipio')->get();
        return $municipio;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(CreateEmpresaRequest $request)
    public function store(Request $request)
    {
        // dd($request->all());
        $camaraComercio = $request->camaracomercio_id;
        $parts = explode('-', $camaraComercio);
        $camaraComercio = trim($parts[0]);

        $obligaciones = json_encode($request['obligaciones']);
        $empleados = json_encode($request['empleados']);
        $ciudades = json_encode($request['obligacionesmunicipales']);
        $codigoObligaciones = json_encode($request['codigo_obligacionmunicipal']);
        $otrasentidades = json_encode($request['otras_entidades']);
        $ciiu = json_encode($request['ciiu']);
        $camara_comercio_establecimientos = json_encode($request['camara_comercio_establecimientos']);
        $nit = $request->NIT;
        $ica_usuario = json_encode($request->usuario_ica);
        $ica_contrasena = json_encode($request->icaclaveportal);

        if (in_array(null, $request->usuario_ica, true) == false || in_array(null, $request->icaclaveportal, true) == false) {
            if (in_array(null, $request->usuario_ica, true) == true || in_array(null, $request->icaclaveportal, true) == true) {
                return response()->json(['error' => 'No se permiten valores nulos en los campos de usuarios ICA o contraseñas ICA.'], 400);
            }
        }


        $request = $request->merge(['obligaciones' => $obligaciones]);
        $request = $request->merge(['empleados' => $empleados]);
        $request = $request->merge(['ciudades' => $ciudades]);
        $request = $request->merge(['codigo_obligacionmunicipal' => $codigoObligaciones]);
        $request = $request->merge(['otras_entidades' => $otrasentidades]);
        $request = $request->merge(['ciiu' => $ciiu]);
        $request = $request->merge(['camaracomercio_id' => $camaraComercio]);
        $request = $request->merge(['camara_comercio_establecimientos' => $camara_comercio_establecimientos]);
        $request = $request->merge(['usuario_ica' => $ica_usuario]);
        $request = $request->merge(['icaclaveportal' => $ica_contrasena]);

        // Mapear entradas de IVA a los nombres de columnas en la BD y crear la empresa
        $ivaData = [];
        for ($i = 1; $i <= 3; $i++) {
            $inputKey = "ivaGenerado{$i}";
            $colKey = "iva_generado_codigo_{$i}";
            $ivaData[$colKey] = $request->has($inputKey) ? $request->input($inputKey) : null;
        }
        for ($i = 1; $i <= 4; $i++) {
            $inputKey = "ivaDescontable{$i}";
            $colKey = "iva_descontable_codigo_{$i}";
            $ivaData[$colKey] = $request->has($inputKey) ? $request->input($inputKey) : null;
        }

        $request = $request->merge($ivaData);

        $empresa = Empresa::create($request->all());
        $request = $request->merge(['empresa_id' => $empresa->id]);

        Clasificacion::create($request->all());

        // Verifica si se proporciona un nuevo archivo PDF para 'rut'
        if ($request->hasFile('rut')) {
            $file = $request->file('rut');
            $filename = 'rut-' . $nit . "." . $file->getClientOriginalExtension();

            // Elimina el archivo anterior si existe
            if (file_exists(public_path('data/Rut/' . $filename))) {
                unlink(public_path('data/Rut/' . $filename));
            }
            // Mueve y guarda el nuevo archivo PDF
            $file->move(public_path('data/Rut/'), $filename);

            $empresa->rut = $filename;
        }

        // Verifica si se proporciona un nuevo archivo para 'contrato'
        if ($request->hasFile('contrato')) {
            $file = $request->file('contrato');
            $filename2 = 'contrato-' . $nit . "." . $file->getClientOriginalExtension();

            // Elimina el archivo anterior si existe
            if (file_exists(public_path('data/Contrato/' . $filename2))) {
                unlink(public_path('data/Contrato/' . $filename2));
            }
            // Mueve y guarda el nuevo archivo
            $file->move(public_path('data/Contrato/'), $filename2);

            $empresa->contrato = $filename2;
        }

        // Uso de la función genérica para cada archivo
        $firmaName = $this->handleFileUpload($request->file('firmarepresentante'), 'representante_firma', 'default.jpg');
        $firmaNamerevisor = $this->handleFileUpload($request->file('firmarevisorfiscal'), 'revisor_firma', 'default.jpg');
        $logoName = $this->handleFileUpload($request->file('logocliente'), 'logo_cliente', 'default.jpg');
        $request = $empresa->firmarepresentante = $firmaName;
        $request = $empresa->firmarevisorfiscal = $firmaNamerevisor;
        $request = $empresa->logocliente = $logoName;
        // Guarda los cambios en la base de datos

        $empresa->save();

        // Obtener el ID de la empresa recién creada
        $idNuevaEmpresa = $empresa->id;
        FechasCalendarioTributarioController::Datos($idNuevaEmpresa);
        FechasCalendarioTributarioController::Datosmunicipales($idNuevaEmpresa);
        FechasCalendarioTributarioController::Datosotrasentidades($idNuevaEmpresa);

        // return redirect()->route('admin.empresas.index')->with('message', 'La empresa se ha creado exitosamente.')->with('color', 'success');
        return 'success';
    }

    private function handleFileUpload($file, $path, $defaultName)
    {
        if ($file == null) {
            return $defaultName; // Retorna el nombre por defecto si no hay archivo
        } else {
            // Genera un nombre único para el archivo
            $fileName = Str::uuid() . '.' . $file->extension();
            // Almacena el archivo en la ruta especificada
            $file->storeAs($path, $fileName, 'public');
            return $fileName;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('VER_EMPRESAS'), Response::HTTP_UNAUTHORIZED);

        $empresa = Empresa::find($id);
        $clasificaciones = Clasificacion::where('empresa_id', $id)->get();

        if (!empty($empresa->obligaciones)) {
            $obligaciones = ObligacionDian::select('nombre', 'codigo')->whereIn('codigo', json_decode($empresa->obligaciones))->orderBy('codigo')->get();
        } else {
            $obligaciones = [];
        }
        if (!empty($empresa->empleados)) {
            $users = User::selectRaw('CONCAT(nombres, " ", apellidos) as nombre_completo, id')->whereIn('id', json_decode($empresa->empleados))->get();
        } else {
            $users = [];
        }

        if (!empty($empresa->codigo_obligacionmunicipal) && $empresa->codigo_obligacionmunicipal != "null") {
            $obligacionesMunicipales = ObligacionesMunicipalesDian::select('nombre', 'codigo')->whereIn('codigo', json_decode($empresa->codigo_obligacionmunicipal))->orderBy('codigo')->get();
        } else {
            $obligacionesMunicipales = [];
        }

        if (!empty($empresa->camara_comercio_establecimientos) && $empresa->camara_comercio_establecimientos != "null") {
            $camaraComercio = CamarasComercio::select('id', 'nombre')->whereIn('id', json_decode($empresa->camara_comercio_establecimientos))->orderBy('nombre')->get();
        } else {
            $camaraComercio = [];
        }

        $camaraComercioPrincipal = CamarasComercio::select('id', 'nombre')->where('id', $empresa->camaracomercio_id)->first();

        $docList = [];

        if ($empresa->rut) {
            $docList['file_documento_1'] = 'data/Rut/' . $empresa->rut;
        }
        if ($empresa->contrato) {
            $docList['file_documento_4'] = 'data/Contrato/' .  $empresa->contrato;
        }

        return view('admin.empresas.show', compact('empresa', 'obligaciones', 'users', 'obligacionesMunicipales', 'docList', 'camaraComercio', 'camaraComercioPrincipal',  'clasificaciones'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_EMPRESAS'), Response::HTTP_UNAUTHORIZED);

        $empresa = Empresa::find($id);

        $clasificacion = Clasificacion::where('empresa_id', $id)->get()->last();

        if ($clasificacion == null) {
            $clasificacion = new Clasificacion;
        }

        $frecuencias = Frecuencia::pluck('nombre', 'id')->prepend('Selecciona una frecuencia');
        $obligaciones = ObligacionDian::all()->sortBy('codigo');
        $ciiu = CodigoCiiu::all()->sortBy('codigo');
        $departamentos = DepartamentosCiudades::pluck('departamento', 'c_digo_dane_del_departamento');
        $obligacionesMunicipales = json_decode($empresa->obligacionesmunicipales);
        $camarascomercio = CamarasComercio::all();
        $empleados = User::orderBy('nombres')->selectRaw('CONCAT(nombres, " ", apellidos) as nombre_completo, id')->whereNotIn('role_id', [7])->pluck('nombre_completo', 'id')->toArray();
        $obligacionesmunicipalescodigo = ObligacionesMunicipalesDian::all()->sortBy('codigo');
        $otrasentidades = OtrasEntidadesCT::all()->sortBy('codigo');
        $usuarios_ica = json_decode($empresa->usuario_ica);
        $clave_ica = json_decode($empresa->icaclaveportal);
        $companias = Empresa::orderBy('razon_social')->pluck('razon_social', 'nit');

        return view('admin.empresas.edit', compact(
            'frecuencias',
            'empresa',
            'obligaciones',
            'departamentos',
            'ciiu',
            'obligacionesMunicipales',
            'camarascomercio',
            'empleados',
            'obligacionesmunicipalescodigo',
            'otrasentidades',
            'clasificacion',
            'usuarios_ica',
            'clave_ica',
            'companias',
        ));
    }


    // Función genérica para manejar la actualización de archivos
    function handleFileUpdate($file, $path, $currentFileName, $defaultName)
    {
        if ($file != null) {
            // Si el archivo actual no es el archivo por defecto, eliminarlo
            if ($currentFileName != $defaultName) {
                $filePath = public_path("storage/$path/" . $currentFileName);
                if (file_exists($filePath)) {
                    unlink($filePath); // Elimina el archivo anterior
                }
            }

            // Generar un nombre único para el nuevo archivo
            $fileName = Str::uuid() . '.' . $file->extension();
            // Almacenar el nuevo archivo en la ruta especificada
            $file->storeAs($path, $fileName, 'public');
            return $fileName; // Retorna el nombre del nuevo archivo
        }

        // Si no se sube un nuevo archivo, retorna el nombre actual
        return $currentFileName;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmpresaRequest $request, $id)
    {
        $cliente = Empresa::find($id);
        $clasificacion = Clasificacion::where('empresa_id', $id)->where('anio', $request->anio)->first();


        // Mapear entradas de IVA a los nombres de columnas en la BD si el usuario las actualizó
        $ivaData = [];
        for ($i = 1; $i <= 3; $i++) {
            $inputKey = "ivaGenerado{$i}";
            $colKey = "iva_generado_codigo_{$i}";
            if ($request->has($inputKey)) {
                $ivaData[$colKey] = $request->input($inputKey);
            }
        }
        for ($i = 1; $i <= 4; $i++) {
            $inputKey = "ivaDescontable{$i}";
            $colKey = "iva_descontable_codigo_{$i}";
            if ($request->has($inputKey)) {
                $ivaData[$colKey] = $request->input($inputKey);
            }
        }

        $request = $request->merge($ivaData);


        // Procesar los campos JSON
        if (in_array(null, $request->usuario_ica, true) == false || in_array(null, $request->icaclaveportal, true) == false) {
            if (in_array(null, $request->usuario_ica, true) == true || in_array(null, $request->icaclaveportal, true) == true) {
                return response()->json(['error' => 'No se permiten valores nulos en los campos de usuarios ICA o contraseñas ICA.'], 400);
            }
        }

        $camaraComercio = $request->camaracomercio_id;
        $parts = explode('-', $camaraComercio);
        $camaraComercio = trim($parts[0]);

        $request = $request->merge(['camaracomercio_id' => $camaraComercio]);

        // Actualiza los campos excepto 'rut' y 'contrato'
        $cliente->update($request->except(['rut', 'contrato', 'firmarepresentante', 'firmarevisorfiscal', 'logocliente']));

        $request = $request->merge(['empresa_id' => $id]);

        $inputsClasificacion = $request->only([
            'anio',
            'regimen_simple_tributacion',
            'ingresos_gravados',
            'ingresos_exentos',
            'ingresos_excluidos',
            'ingresos_no_gravados',
            'devoluciones',
            'total_ingresos',
            'actividad_1',
            'actividad_2',
            'actividad_3',
            'actividad_4',
            'operaciones_excentas',
            'actividades_exp_imp',
            'gran_contribuyente',
            'ingresos_brutos_fiscales_anio_anterior',
            'formato_conciliacion_fiscal',
            'activos_brutos_diciembre_anio_anterior',
            'ingreso_brutos_diciembre_anio_anterior',
            'revisor_fiscal',
            'patrimonio_brutos_diciembre_anio_anterior',
            'ingreso_brutos_tributario_diciembre_anio_anterior',
            'declaracion_tributaria_firma_contador',
            'empresa_id'
        ]);

        if ($clasificacion) {
            $clasificacion->update($inputsClasificacion);
        } else if ($request->anio) {
            Clasificacion::create($inputsClasificacion);
        }

        // Verifica si se proporciona un nuevo archivo PDF para 'rut'
        if ($request->hasFile('rut')) {
            $file = $request->file('rut');
            $filename = 'rut-' . $cliente->NIT . "." . $file->getClientOriginalExtension();

            // Elimina el archivo anterior si existe
            if (file_exists(public_path('data/Rut/' . $filename))) {
                unlink(public_path('data/Rut/' . $filename));
            }

            // Mueve y guarda el nuevo archivo PDF
            $file->move(public_path('data/Rut/'), $filename);

            // Actualiza el campo 'rut' en la base de datos
            $cliente->rut = $filename;
        }

        // Verifica si se proporciona un nuevo archivo para 'contrato'
        if ($request->hasFile('contrato')) {
            $file = $request->file('contrato');
            $filename2 = 'contrato-' . $cliente->NIT . "." . $file->getClientOriginalExtension();

            // Elimina el archivo anterior si existe
            if (file_exists(public_path('data/Contrato/' . $filename2))) {
                unlink(public_path('data/Contrato/' . $filename2));
            }

            // Mueve y guarda el nuevo archivo
            $file->move(public_path('data/Contrato/'), $filename2);

            // Actualiza el campo 'contrato' en la base de datos
            $cliente->contrato = $filename2;
        }

        $empleados = EmpleadoCliente::select('user_id')->where('empresa_id', $id)->get();


        if ($id != 1) {
            if ($request['estado']) {
                $cliente->estado = 1;
                foreach ($empleados as $empleado) {
                    $user = User::withTrashed()->where('id', $empleado->user_id)->first();
                    $user->estado = 'ACTIVO';
                    $user->restore();
                    $user->save();
                }
            } else {
                $cliente->estado = 0;
                foreach ($empleados as $empleado) {
                    $user = User::withTrashed()->where('id', $empleado->user_id)->first();
                    $user->estado = 'INACTIVO';
                    $user->delete();
                    $user->save();
                }
            }
        }
        // Actualiza la firma del representante
        $firmaName = $this->handleFileUpdate($request->file('firmarepresentante'), 'representante_firma', $cliente->firmarepresentante, 'default.jpg');
        // Actualiza la firma del revisor fiscal
        $firmarevisorName = $this->handleFileUpdate($request->file('firmarevisorfiscal'), 'revisor_firma', $cliente->firmarevisorfiscal, 'default.jpg');
        // Actualiza el logo del cliente
        $logoName = $this->handleFileUpdate($request->file('logocliente'), 'logo_cliente', $cliente->logocliente, 'default.jpg');
        // Asigna los nombres actualizados a los campos correspondientes del cliente
        $cliente->firmarepresentante = $firmaName;
        $cliente->firmarevisorfiscal = $firmarevisorName;
        $cliente->logocliente = $logoName;
        $cliente->empresaasociada = $request->input('empresaasociada');
        $cliente->operador = $request->input('operador');
        // Guarda los cambios en la base de datos
        $cliente->save();

        // actualiza los datos de la empresa para la tabla fechas por empresa
        FechasCalendarioTributarioController::Datos($id);
        FechasCalendarioTributarioController::Datosmunicipales($id);
        FechasCalendarioTributarioController::Datosotrasentidades($id);
        // return redirect()->route('admin.empresas.index')->with('message', 'La empresa se ha actualizado exitosamente.')->with('color', 'success');
        return 'success';
    }

    public function findUVT($anio)
    {
        $uvt = UVT::where('anio', $anio)->first();
        return json_encode($uvt);
    }

    public function empresasExport()
    {
        if (Auth::user()->role_id == 1) {
            $empresas = Empresa::select('razon_social', 'id')->with('frecuencia')->select('empresas.*')->orderBy('razon_social')->get();
        } else {
            $empresas = Empresa::select('razon_social', 'id')->whereJsonContains('empleados', (string)Auth::user()->id)->with('frecuencia')->select('empresas.*')->orderBy('razon_social')->get();
        }

        return Excel::download(new EmpresasExport($empresas), 'Lista de Empresas.xlsx');
    }
}
