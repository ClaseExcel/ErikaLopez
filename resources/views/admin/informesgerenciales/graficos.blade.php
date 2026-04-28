{{-- graficos --}}
<div class="row">
    <div class="col-12 col-xl-6 mb-3 container-grafico" style="display:none ;">
        <div class="card p-0 m-0 border-0">
            <div class="card-body">
                <div id="grafico-composicion-costos-gastos"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6 mb-3 container-grafico" style="display:none ;">
        <div class="card p-0 m-0 border-0">
            <div class="card-body">
                <div id="grafico-composicion-ingresos"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">   

    <div class="col-12 col-xl-6 mb-3 container-grafico" style="display:none ;">
        <div class="card p-0 m-0 border-0">
            <div class="card-body">
                <div id="grafico-composicion-situacion"></div>
            </div>
        </div>
    </div>

    {{-- //grafico balance de situacion --}}
    <div class="col-12 col-xl-6 mb-3 container-grafico" style="display:none ;">
        <div class="card p-0 m-0 border-0">
            <div class="card-body">
                <div id="grafico-balance-situacion"></div>
            </div>
        </div>
    </div>

</div>


<div class="row d-flex justify-content-center">
    {{-- //grafico de ventas --}}
    {{-- cuentas bancarias --}}
    {{-- <div class="col-12 col-xl-6 mb-3 container-grafico" style="display:none ;">
        <div class="card p-0 m-0 border-0">
            <div class="card-body grafico-responsive">
                <div id="grafico-cuentas-bancarias"></div>
            </div>
        </div>
    </div> --}}



    {{-- grafico ingresos operacionales --}}
    <div class="col-12 col-xl-6 mb-3 container-grafico" style="display:none ;" id="ingresos-operacionales-container">
        <div class="card p-0 m-0 border-0">
            <div class="card-body grafico-responsive">
                <div id="grafico-ingresos-operacionales"></div>
            </div>
        </div>
    </div>

    {{-- grafico de devoluaciones --}}
    <div class="col-12 col-xl-6 mb-3 container-grafico" style="display:none ;" id="devoluciones-container">
        <div class="card p-0 m-0 border-0">
            <div class="card-body grafico-responsive">
                <div id="grafico-devoluciones"></div>
            </div>
        </div>
    </div>

</div>


<div class="row d-flex justify-content-center">
    {{-- grafico gastos --}}
    <div class="col-12 col-xl-6 mb-3 container-grafico" style="display:none ;" id="gastos-container">
        <div class="card p-0 m-0 border-0">
            <div class="card-body grafico-responsive">
                <div id="grafico-gastos"></div>                
            </div>
        </div>
    </div>
    {{-- grafico-costo-ventas --}}
    <div class="col-12 col-xl-6 mb-3 container-grafico" style="display:none ;" id="costo-ventas-container">
        <div class="card p-0 m-0 border-0">
            <div class="card-body grafico-responsive">
                <div id="grafico-costo-ventas"></div>
            </div>
        </div>
    </div>

</div>

<div class="row d-flex justify-content-center">

    {{-- grafico costo produccion --}}
    <div class="col-12 col-xl-6 mb-3 container-grafico costo-produccion-container" style="display:none ;" id="costo-produccion-container">
        <div class="card p-0 m-0 border-0">
            <div class="card-body grafico-responsive">
                <div id="grafico-costo-produccion"></div>                
            </div>
        </div>
    </div>
        {{-- grafico cartera --}}
    <div class="col-12 col-xl-6 mb-3 container-grafico" style="display:none ;" id="cartera-container">
        <div class="card p-0 m-0 border-0">
            <div class="card-body grafico-responsive">
                <div id="grafico-cartera"></div>
            </div>
        </div>
    </div>

</div>

<div class="row d-flex justify-content-center" id="row-graficos-impuestos">
    {{-- grafico impuesto de renta --}}
    {{-- <div class="col-12 col-xl-6 mb-3 container-grafico" style="display:none ;" id="impuesto-renta-container">
        <div class="card p-0 m-0 border-0">
            <div class="card-body">
                <div id="grafico-impuesto-renta"></div>
            </div>
        </div>
    </div> --}}

    {{-- grafico iva --}}
    <div class="col-12 col-sm-6 mb-3 container-grafico " id="iva-container" style="display:none ;">
        <div class="card p-0 m-0 border-0" id="card-grafico-iva">
            <div class="card-body grafico-responsive" id="grafico-iva">
                <div id="grafico-iva-generado"></div>
                <div id="grafico-iva-compras"></div>                
            </div>
        </div>
    </div>
</div>

