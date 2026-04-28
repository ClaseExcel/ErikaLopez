<style>

    :root {
        --color-1: #45bbef;
        --color-2: #a4e5ff;
        --color-3: #14749b;
    }
     
    .bg-informe {
        background: white !important;
        /* background: linear-gradient(180deg, #544fc5 0%, #32143b 100%) ; */
        color: rgb(1 72 177 / 70%) !important;
    }

    .alert {
        border-radius: 15px !important;
    }

    .alert {
        min-width: 220px;
    }

    .alert {
        color: #32143b !important;
    }

    .alert-data strong {
        color: var(--color-1) !important;
    }

    .alert-gradient {
        background: rgb(255, 255, 255);
        background: linear-gradient(0deg, #ffffff 97%, var(--color-2) 100%);
    }

    /* .alert-data strong para resoluciones desde 1200 hasta 1470 fontnt-size:14px*/
    @media (min-width: 1200px) and (max-width: 1470px) {
        .alert-data h4 {
            font-size: 18px;
        }
    }

    .page-break {
        page-break-after: always;
    }


    hacer que la tab activa tenga el color de la que esta imnactiva .nav-link-informes.active {
        background-color: #f8f9fa !important;
        color: #32143b !important;
    }

    .nav-link-informes.nav-link {
        color: #32143b !important;
    }

    .active {
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
        border-bottom: 2px solid #ffffff !important;
    }

    #imagen-preview-dian,
    #imagen-preview-ica,
    #imagen-preview-renta,
    #imagen-preview-simple,
    #imagen-preview-cuentas {
        display: none;
        max-height: 600px;
        max-width: 100%;
        border-radius: 13px;
    }

    #imagen-preview-dian:hover,
    #imagen-preview-ica:hover,
    #imagen-preview-renta:hover,
    #imagen-preview-simple:hover,
    #imagen-preview-cuentas:hover {
        cursor: pointer;
        opacity: 0.8;
    }

    .accordion-button:not(.collapsed) {
        color: #00719d;
        background-color: #3fbdee28;
    }

    .accordion-button:focus {
        box-shadow: 0 0 0 .25rem #3fbdee5c;
    }

    .accordion-button {
        color: #00719de3;
    }

    g.highcharts-label.highcharts-data-label.highcharts-data-label-color-1 text {
        font-family: "Inter Tight", sans-serif;
        font-size: 13px;
        /* font-weight: normal; */
    }

    .highcharts-text-outline {
        font-family: "Inter Tight", sans-serif;
        font-size: 13px;
        /* font-weight: normal; */
    }

    .texto-grafico {
        font-family: "Inter Tight", sans-serif;
        font-size: 13px;
        /* font-weight: 500; */
    }

    .texto-grafico-iva {
        font-family: "Inter Tight", sans-serif;
        font-size: 13px;
        /* font-weight: 500; */
    }

    .grafico-responsive {
        width: 100%;
        height: auto;
        max-width: 460px;
        margin: 0 auto;
    }

    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1500;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }
</style>
