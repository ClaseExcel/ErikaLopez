<!-- Modal Mejorado -->
<div class="modal fade" id="modalDescarga" tabindex="-1" aria-labelledby="modalDescargaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered border-0 bg-transparent">
      <div class="modal-content shadow-lg rounded-4">
          <div class="modal-header card-header text-white">
              <h5 class="modal-title" id="modalDescargaLabel">
                  <i class="fa-solid fa-calendar-alt"></i> Seleccionar Fechas
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4 bg-light">
              <form id="formDescarga" action="" method="POST">
                  @csrf
                  <div class="mb-3">
                      <input type="hidden" name="tipo" id="tipo" value='fechas'>
                      <label for="filtro" class="form-label fw-normal">Filtrar por:</label>
                      <select class="form-control border btn-radius" id="filtro" name="filtro" onchange="toggleFiltro()">
                          <option value="fechas" selected>Por Fechas</option>
                          <option value="creador">Por Creador</option>
                          <option value="responsable">Por Responsable</option>
                      </select>
                  </div>
                  <div id="filtroFechas">
                      <div class="mb-3">
                          <label for="fecha_inicio" class="form-label text fw-normal">Generar informe mensual</label>
                      </div>
                      <div class="mb-3">
                          <label for="fecha_inicio" class="form-label text fw-normal">Fecha de Inicio</label>
                          <input type="date" class="form-control border btn-radius" id="fecha_inicio" name="fecha_inicio" required>
                      </div>
                      
                      <div class="mb-3">
                          <label for="fecha_fin" class="form-label fw-normal">Fecha de Fin</label>
                          <input type="date" class="form-control border btn-radius" id="fecha_fin" name="fecha_fin" required>
                      </div>
                  </div>
                  <div id="filtroEmpresa" style="display: none;">
                      <div class="mb-3">
                          <label for="fecha_inicio"  id="filtroLabelprincipal" class="form-label text fw-normal">Generar informe</label>
                      </div>
                      <div class="mb-3">
                          <input type="hidden" name="user_id" id="user_id">
                          <label class="fw-normal" id="filtroLabel">Seleccionar Usuario</label>
                          <input class="form-control" list="datalistOptions" placeholder="Escribe Para Buscar..."
                                  name="user" id="user"
                                  autocomplete="off">
                              <datalist id="datalistOptions">
                                @foreach ($responsables as $responsable)
                                    <option
                                        value="{{ $responsable->id }} - {{ $responsable->nombres . ' ' . $responsable->apellidos }}"
                                        data-id="{{ $responsable->id }}">

                                    </option>
                                @endforeach
                              </datalist>
                              
                      </div>
                  </div>
                  
                  <div class="d-flex justify-content-center">
                      <button type="button" class="btn btn-back  border btn-radius" onclick="generarReporte('excel')">
                          <i class="fa-solid fa-file-excel"></i> Descargar Excel
                      </button>
                  </div>
              </form>
              <!-- Ventana de carga -->
              <div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0, 0, 0, 0.5); z-index:9999; text-align:center;">
                  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:20px; border-radius:10px;">
                      <h3>Generando archivo, por favor espere...</h3>
                      <img src="https://i.gifer.com/ZKZg.gif" alt="Cargando..." width="100">
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>

<script>
  function generarReporte(tipo) {
      let form = document.getElementById("formDescarga");
      let fechaInicio = document.getElementById("fecha_inicio").value;
      let fechaFin = document.getElementById("fecha_fin").value;
      let tiposerver = document.getElementById("tipo").value;
      let nombre = ""; // Para almacenar el nombre del usuario seleccionado en el datalist
      document.getElementById('user').addEventListener('input', function() {
          let input = this.value;
         
          let datalist = document.getElementById('datalistOptions').options;
          for (let option of datalist) {
              if (option.value === input) {
                  document.getElementById('user_id').value = option.dataset.id;
                  break;
              }
          }
      });
      if (tiposerver === 'fechas') {
          if (!fechaInicio || !fechaFin) {
              Swal.fire({ icon: 'warning', title: 'Campos Incompletos', text: 'Debe seleccionar un rango de fechas.' });
              return;
          }

          // // Validar que el rango no supere un mes
          let startDate = new Date(fechaInicio);
          let endDate = new Date(fechaFin);

          // Validar que la fecha de fin no sea menor que la de inicio
          if (endDate < startDate) {
              Swal.fire({ 
                  icon: 'error', 
                  title: 'Rango inválido', 
                  text: 'La fecha final no puede ser menor que la fecha de inicio.' 
              });
              return;
          }

          // Validar que el rango no supere un mes
          let diffMonths = (endDate.getFullYear() - startDate.getFullYear()) * 12 + (endDate.getMonth() - startDate.getMonth());
          

          if (tipo === 'pdf' && diffMonths > 2) {
              Swal.fire({ icon: 'error', title: 'Rango Inválido', text: 'El rango de fechas no puede superar un mes para generar el PDF.' });
              return;
          } else if (tipo !== 'pdf' && diffMonths > 12) {
              Swal.fire({ icon: 'error', title: 'Rango Inválido', text: 'El rango de fechas no puede superar 12 meses para generar el excel.' });
              return;
          }
      } else if (tiposerver === 'creador' || tiposerver === 'responsable') {
        nombre=document.getElementById('user').value;
        nombre = nombre.split(' - ')[1];
        if (!nombre) {
            Swal.fire({ icon: 'warning', title: 'Campos Incompletos', text: 'Debe seleccionar un responsable.' });
            return;
        }
      }else{
          Swal.fire({ icon: 'warning', title: 'Campos Incompletos', text: 'Debe seleccionar fechas o un responsabñe.' });
              return;
      }

      // Mostrar ventana de carga
      document.getElementById("loadingOverlay").style.display = "block";

      // Configurar la URL del formulario
      if (tipo === 'pdf') {
          form.action = "{{ route('admin.calendario.descargarPDFCompleto') }}";
      } else {
          form.action = "{{ route('admin.actividad_cliente.descargarExcel') }}";
      }

      // Crear objeto FormData para enviar con fetch()
      let formData = new FormData(form);
      fetch(form.action, {
          method: "POST",
          body: formData
      })
      .then(response => {
          if (!response.ok) {
              return response.json().then(errorData => { throw errorData; });
          }
          return response.blob(); // Recibir el archivo como blob
      })
      .then(blob => {
          let link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          // Generar el nombre del archivo dinámicamente
          if (tiposerver === "creador" || tiposerver === "responsable") {
              link.download = `informe_actividades_${nombre}.xlsx`;
          } else {
              link.download = `informe_actividades_${fechaInicio}_al_${fechaFin}.xlsx`;
          }
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
      })
      .catch(error => {
          if (error.error) {
              Swal.fire({ icon: 'error', title: 'Error', text: error.error });
          } else {
              Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un problema al generar el reporte.' });
          }
      })
      .finally(() => {
          // Ocultar ventana de carga siempre al finalizar
          document.getElementById("loadingOverlay").style.display = "none";
      });
  }
  function toggleFiltro() {
      let filtro = document.getElementById("filtro").value;
      let fechaInicio = document.getElementById("fecha_inicio");
      let fechaFin = document.getElementById("fecha_fin");
      let responsable = document.getElementById("user_id");
      let tiposerver = document.getElementById("tipo"); 
      let filtroLabel = document.getElementById("filtroLabel");
      let filtroLabelprincipal = document.getElementById("filtroLabelprincipal");
      let inputUser = document.getElementById("user");

      if (filtro === "fechas") {
          document.getElementById("filtroFechas").style.display = "block";
          document.getElementById("filtroEmpresa").style.display = "none";
          responsable.value = "";
          inputUser.value = ""; // Limpiar el valor del input de usuario
          tiposerver.value = "fechas";
      } else {
          document.getElementById("filtroFechas").style.display = "none";
          document.getElementById("filtroEmpresa").style.display = "block";
          fechaInicio.value = "";
          fechaFin.value = "";
          inputUser.value = ""; // Limpiar el valor del input de usuario
          tiposerver.value = filtro;
          filtroLabelprincipal.innerText = filtro === "creador" ? "Generar informe por Creador" : "Generar informe por Responsable";
          filtroLabel.innerText = filtro === "creador" ? "Seleccionar Creador" : "Seleccionar Responsable";
      }
  }
</script>