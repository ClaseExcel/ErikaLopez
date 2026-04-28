 <div class="row">
     <input type="hidden" name="gestion_humana_id" value="{{ $gestion_humana->id }}">

     <div class="col-xl-12">
         <div class="form-floating mb-3">
             <select class="form-select" name="concepto_id" id="concepto_id" required>
                 <option value="">Seleccionar una opción</option>
                 @foreach ($conceptos as $concepto)
                     <option value={{ $concepto->id }}
                         {{ old('concepto_id', $evento->concepto_id == $concepto->id) ? 'selected' : '' }}>
                         {{ $concepto->nombre }}
                     </option>
                 @endforeach
             </select>
             <label class="fw-normal" for="concepto_id">Concepto <b class="text-danger">*</b></label>
             @if ($errors->has('concepto_id'))
                 <span class="text-danger">{{ $errors->first('concepto_id') }}</span>
             @endif
         </div>

     </div>

     <div class="col-xl-6">
         <div class="form-floating mb-3">
             <input type="date" name="fecha_inicio" id="fecha_inicio"
                 value="{{ old('fecha_inicio', $evento->fecha_inicio) }}" class="form-control" placeholder=" " />
             <label for="fecha_inicio" class="fw-normal">Fecha inicio <b class="text-danger">*</b></label>
             @if ($errors->has('fecha_inicio'))
                 <span id="fecha_inicio" class="text-danger">{{ $errors->first('fecha_inicio') }}</span>
             @endif
         </div>
     </div>


     <div class="col-xl-6">
         <div class="form-floating mb-3">
             <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin', $evento->fecha_fin) }}"
                 class="form-control" placeholder=" " />
             <label for="fecha_fin" class="fw-normal">Fecha fin <b class="text-danger">*</b></label>
             @if ($errors->has('fecha_fin'))
                 <span id="fecha_fin" class="text-danger">{{ $errors->first('fecha_fin') }}</span>
             @endif
             <span id="fecha_fin_error" class="text-danger"></span>
         </div>
     </div>

     <div style="display: none;" id="certificados">
         <div class="row">
             <div class="col-xl-6">
                 <div class="mb-3">
                     <label for="certificado_retiro_arl" class="fw-normal">Certificado de retiro ARL</label>
                     <input type="file" class="form-control" id="certificado_retiro_arl" name="certificado_arl"
                         accept=".pdf,.jpg,.jpeg,.png">
                     @if ($errors->has('certificado_retiro_arl'))
                         <span class="text-danger">{{ $errors->first('certificado_retiro_arl') }}</span>
                     @endif
                 </div>
             </div>

             <div class="col-xl-6">
                 <div class="mb-3">
                     <label for="certificado_retiro_caja" class="fw-normal">Certificado retiro caja de
                         compensación</label>
                     <input type="file" class="form-control" id="certificado_retiro_caja" name="certificado_caja"
                         accept=".pdf,.jpg,.jpeg,.png">
                     @if ($errors->has('certificado_retiro_caja'))
                         <span class="text-danger">{{ $errors->first('certificado_retiro_caja') }}</span>
                     @endif
                 </div>
             </div>

             <div class="col-xl-6">
                 <div class="mb-3">
                     <label for="liquidacion" class="fw-normal">Liquidación</label>
                     <input type="file" class="form-control" id="liquidacion" name="liquida"
                         accept=".pdf,.jpg,.jpeg,.png">
                     @if ($errors->has('liquidacion'))
                         <span class="text-danger">{{ $errors->first('liquidacion') }}</span>
                     @endif
                 </div>
             </div>
         </div>
     </div>


     <script>
         document.addEventListener('DOMContentLoaded', function() {
             const form = document.querySelector('form');
             if (form) {
                 form.addEventListener('submit', function(e) {
                     const fechaInicio = document.getElementById('fecha_inicio').value;
                     const fechaFin = document.getElementById('fecha_fin').value;
                     let valid = true;
                     let errorMsg = '';

                     if (fechaInicio && fechaFin) {
                         if (fechaFin < fechaInicio) {
                             valid = false;
                             errorMsg = 'La fecha final no puede ser menor que la inicial.';
                         }
                     }
                     document.getElementById('fecha_fin_error')?.classList.remove('d-block');

                     if (!valid) {
                         e.preventDefault();
                         document.getElementById('fecha_fin_error').textContent = errorMsg;
                         document.getElementById('fecha_fin_error').classList.add('d-block');
                     }
                 });
             }
         });

         document.getElementById('concepto_id').addEventListener('change', function() {
             var certificados = document.getElementById('certificados');

             if (this.value == 11) {
                 certificados.style.display = 'block';
             } else {
                 certificados.style.display = 'none';
             }
         });

         var concepto = document.getElementById('concepto_id').value;
         var certificados = document.getElementById('certificados');
         
         if (concepto == 11) {
             certificados.style.display = 'block';
         } else {
             certificados.style.display = 'none';
         }
     </script>

     <div class="col-md-12">
         <div class="form-floating mb-3">
             <textarea id="observacion" name="observacion" rows="1" class="form-control" style="height: 100px">{{ $evento->observacion }}</textarea>
             <label for="observacion" class="fw-normal">Observación</label>
         </div>
     </div>
 </div>
