<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ActionButtonTrait;
use App\Http\Requests\StoreGestionRequest;
use App\Http\Requests\UpdateGestionRequest;
use App\Models\Empresa;
use App\Models\Gestion;
use App\Models\NotificacionesGestion;
use App\Models\User;
use App\Notifications\GestionesNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class GestionController extends Controller
{
    use ActionButtonTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('ACCEDER_GESTION'), Response::HTTP_UNAUTHORIZED);

        if ($request->ajax()) {
            $gestiones =  Gestion::with('cliente', 'usuario_create')->select('gestiones.*');

            return DataTables::of($gestiones)
                ->addColumn('actions', function ($gestiones) {
                    // Lógica para generar las acciones para cada registro de empleados

                    $generarPdf = '<a href="' . route("admin.pdf.gestion", $gestiones->id) . '" title="Generar acta" class="btn-eliminar px-2 py-0">
                    <i class="fa-solid fa-file-pdf"></i>
                    </a>';

                    $enviarCorreo = '<button type="button" class="btn-enviar-correo px-2 py-0 border-0" data-id="' . $gestiones->id . '" title="Enviar por correo">
                        <i class="fa-solid fa-envelope"></i>
                    </button>';

                    $enviarWhatsapp = '<button type="button" class="btn-enviar-whatsapp px-2 py-0 border-0" data-id="' . $gestiones->id . '" title="Enviar por WhatsApp">
                       <i class="fa-brands fa-whatsapp"></i
                    </button>';

                    $crearActividad = '<a href="' . route("admin.actividad_cliente.create") . '" title="Crear actividad" class="btn-eliminar px-2 py-0">
                    <i class="fa-solid fa-file-circle-plus"></i>
                    </a>';

                    $crearRequerimiento = '<a href="' . route("admin.requerimientos.cliente.create") . '" title="Crear requerimiento" class="btn-eliminar px-2 py-0">
                    <i class="fas fa-file-alt"></i>
                    </a>';

                    return $this->getActionButtons('admin.gestiones', 'GESTION', $gestiones->id) . $generarPdf . $enviarCorreo . $enviarWhatsapp . $crearActividad . $crearRequerimiento;
                })
                ->rawColumns(['actions']) //para que se muestre el codigo html en la tabla
                ->make(true);
        }

        return view('admin.gestiones.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('CREAR_GESTION'), Response::HTTP_UNAUTHORIZED);

        $clientes = Empresa::whereNotIn('id', [1])->where('estado', 1)->select('razon_social', 'id')->orderBy('razon_social')->get();
        return view('admin.gestiones.create', compact('clientes'), ['gestion' => new Gestion]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGestionRequest $request)
    {
        $request = $request->merge(['user_create_gestion_id' =>  Auth::user()->id]);
        $request = $request->merge(['user_update_gestion_id' =>  Auth::user()->id]);

        $gestion = Gestion::create($request->all());

        //Ruta donde se guardan los documentos
        $fileBasePath = storage_path('app/public/gestion');

        $documents = [
            'documento_uno',
            'documento_dos',
            'documento_tres',
            'documento_cuatro',
            'documento_cinco',
            'documento_seis'
        ];

        //Valida documentos y los actualiza
        foreach ($documents as $documentPath) {
            $this->load_file_create($request, $documentPath, $fileBasePath, $gestion->id);
        }

        return redirect()->route('admin.gestiones.index')->with('message', 'Gestión creada exitosamente')->with('color', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('VER_GESTION'), Response::HTTP_UNAUTHORIZED);

        $gestion = Gestion::find($id);
        return view('admin.gestiones.show', compact('gestion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('EDITAR_GESTION'), Response::HTTP_UNAUTHORIZED);
        $gestion = Gestion::find($id);
        $clientes = Empresa::whereNotIn('id', [1])->where('estado', 1)->select('razon_social', 'id')->orderBy('razon_social')->get();
        return view('admin.gestiones.edit', compact('clientes', 'gestion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGestionRequest $request, $id)
    {
        $request = $request->merge(['user_update_gestion_id' =>  Auth::user()->id]);

        $gestion = Gestion::find($id);

        $gestion->update($request->all());

        //Ruta donde se guardan los documentos
        $fileBasePath = storage_path('app/public/gestion');

        $documents = [
            'documento_uno',
            'documento_dos',
            'documento_tres',
            'documento_cuatro',
            'documento_cinco',
            'documento_seis'
        ];

        //Valida documentos y los actualiza
        foreach ($documents as $documentPath) {
            $name = $gestion->$documentPath;
            $this->load_file_update($request, $documentPath, $fileBasePath, $name, $id);
        }

        return redirect()->route('admin.gestiones.index')->with('message', 'Gestión actualizada exitosamente')->with('color', 'success');
    }

    public function load_file_create($request, $path, $basePath, $gestion)
    {

        if ($request->file($path)) {
            $file = $request->file($path);
            $filename = uniqid() . '-' . date('Y-m-d') . '.' . $file->getClientOriginalExtension();
            $fullPath = $basePath . '/' . $path . '/' . $filename;

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            $file->move($basePath . '/' . $path, $filename);
            Gestion::where('id', $gestion)->update([$path => $filename]);
        }
    }

    public function load_file_update($request, $path, $basePath, $oldName, $gestion)
    {
        if ($request->file($path)) {
            $file = $request->file($path);
            $filename = uniqid() . '-' . date('Y-m-d') . '.' . $file->getClientOriginalExtension();
            $fullPath = $basePath . '/' . $path . '/' . $filename;

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            $file->move($basePath . '/' . $path, $filename);
            Gestion::where('id', $gestion)->update([$path => $filename]);
        } else {
            return $oldName;
        }
    }

    public function generarPdf($id)
    {
        $gestion = Gestion::find($id);

        $pdf = PDF::loadView('admin.gestiones.pdf.acta-reunion', ['gestion' => $gestion]);
        return $pdf->download('ACTA_DE_REUNIÓN.pdf');
    }

    public function enviarCorreo(Request $request)
    {
        $request->validate([
            'gestion_id' => 'required|exists:gestiones,id',
            'observacion' => 'nullable|string',
        ]);

        $gestion = Gestion::with('cliente')->findOrFail($request->gestion_id);

        if ($request->correos_adicionales) {
            $correosAdicionales = explode(',', $request->correos_adicionales);
            $correosAdicionales[] = $gestion->cliente->correo_electronico;
        } else {
            $correosAdicionales = [$gestion->cliente->correo_electronico]; // Asegurarse de que siempre haya al menos un correo
        }

        // Usar la misma vista del PDF ya existente
        $pdf = PDF::loadView('admin.gestiones.pdf.acta-reunion', ['gestion' => $gestion]);
        $pdfContent = $pdf->output();

        // Enviar el correo
        Mail::send('emails.gestion', [
            'gestion' => $gestion,
            'observacion' => $request->observacion,
        ], function ($message) use ($correosAdicionales, $pdfContent) {
            $message->to($correosAdicionales) // Campo correcto
                ->subject('Acta de reunión - Gestión')
                ->attachData($pdfContent, 'ACTA_DE_REUNION.pdf', [
                    'mime' => 'application/pdf',
                ]);
        });
        // Guardar en la tabla de notificaciones
        NotificacionesGestion::create([
            'gestion_id' => $gestion->id,
            'usuario_id' => Auth::id(),
            'fecha_envio' => now(),
            'observacion' => $request->observacion,
        ]);

        return response()->json(['message' => 'Correo enviado con éxito']);
    }

    public function enviarWhatsapp(Request $request)
    {
        $gestion = Gestion::with('cliente')->findOrFail($request->gestion_id);
        $empresa = Empresa::where('id', $gestion->cliente_id)->first();
        $recipient = User::find(Auth::user()->id);
        $numero = $request->numero;

        // Validar el número de teléfono
        if (!preg_match('/^\+?[0-9]{10,15}$/', $numero)) {
            return response()->json(['error' => 'Número de teléfono inválido.'], 400);
        }

        $nombrePlantilla = 'notificacion_gestiones';
        // Usar la misma vista del PDF ya existente
        $pdf = PDF::loadView('admin.gestiones.pdf.acta-reunion', ['gestion' => $gestion]);

        $pdfContent = $pdf->output();
        $filename = 'adjunto-acta-' . $gestion->id . ".pdf";
        $filePath = 'storage/documentos-actas/' . $filename;

        // Elimina el archivo anterior si existe
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Guarda el contenido del PDF en la carpeta deseada
        Storage::disk('public')->put('documentos-actas/' . $filename, $pdfContent);

        try {
            $recipient->notify(new GestionesNotification($empresa->razon_social, $filePath, $numero, $nombrePlantilla));
            return response()->json(['success' => 'Notificado.'], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Error el notificar.', 400]);
        }
    }

    public function historialNotificaciones(Request $request)
    {
        if ($request->ajax()) {
            $notificaciones = NotificacionesGestion::with(['gestion.cliente', 'usuario'])->select('notificacionesgestion.*');

            return DataTables::of($notificaciones)
                ->addColumn('empresa', fn($row) => optional(optional($row->gestion)->cliente)->razon_social ?? 'N/A')
                ->addColumn('tipo_gestion', fn($row) => optional($row->gestion)->tipo_visita ?? 'N/A')
                ->addColumn('fecha_visita', fn($row) => optional($row->gestion)->fecha_visita ? \Carbon\Carbon::parse($row->gestion->fecha_visita)->format('d/m/Y') : 'N/A')
                ->addColumn('fecha_envio', fn($row) => \Carbon\Carbon::parse($row->fecha_envio)->format('d/m/Y H:i'))
                ->addColumn('usuario', fn($row) => optional($row->usuario)->nombres . ' ' . optional($row->usuario)->apellidos ?? 'N/A')
                ->addcolumn('correo', fn($row) => optional($row->gestion->cliente)->correo_electronico ?? 'N/A')
                ->addColumn('user_create_gestion_id', fn($row) => optional($row->gestion->usuario_create)->nombres . ' ' . optional($row->gestion->usuario_create)->apellidos ?? 'N/A')
                ->addColumn('observacion', fn($row) => $row->observacion ?? '') // 👈 Esta es la que te faltaba
                ->addColumn('ver_pdf', function ($row) {
                    $url = route('admin.pdf.gestion', $row->gestion_id);
                    return '<a href="' . $url . '" target="_blank" class="btn btn-back border btn-radius"><i class="fas fa-file-pdf"></i></a>';
                })
                ->rawColumns(['ver_pdf'])
                ->make(true);
        }
        return view('admin.gestiones.notificaciones');
    }
}
