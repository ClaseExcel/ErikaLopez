        //validar que se seleccione una compañia
        document.getElementById('compania').addEventListener('change', function() {
            var compania = document.getElementById('empresa_id').value;
            if (compania != '') {
                document.getElementById('mostrarFechaInicial').style.display = 'block';
            } else {
                document.getElementById('mostrarFechaInicial').style.display = 'none';
            }
        });

        //validar que se seleccione una fecha inicial
        document.getElementById('fecharInicial').addEventListener('change', function() {
            var fechaInicial = document.getElementById('fecharInicial').value;
            if (fechaInicial != '') {
                document.getElementById('mostrarFechaFinal').style.display = 'block';
                //la fecha minima de la fecha final es la fecha inicial
                document.getElementById('fecharFinal').setAttribute('min', fechaInicial);
                //limpiar el valor de la fecha final
                document.getElementById('fecharFinal').value = '';

            } else {
                document.getElementById('mostrarFechaFinal').style.display = 'none';
                //quitar el minimo de la fecha final
                document.getElementById('fecharFinal').removeAttribute('min');
            }


        });

        //validar que se seleccione una fecha final
        document.getElementById('fecharFinal').addEventListener('change', function() {
            var fechaFinal = document.getElementById('fecharFinal').value;
            if (fechaFinal != '') {
                document.getElementById("mostrarChecklist").style.display = "block";
                document.getElementById('btn-generar-informe').style.display = 'block';
            } else {
                document.getElementById("mostrarChecklist").style.display =  "none";
                document.getElementById('btn-generar-informe').style.display = 'none';
            }
        });

        function updateCompania(input) {
            let datalist = document.getElementById("datalistOptions").options;
            let hiddenInput = document.getElementById("empresa_id");

            hiddenInput.value = ""; // Limpiar ID si no se encuentra
            for (let option of datalist) {
                if (option.value === input.value) {
                    hiddenInput.value = option.getAttribute("data-id");
                    break;
                }
            }
        }
