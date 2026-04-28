<?php

namespace Database\Seeders;

use App\Models\AgrupacionesNIIF;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgrupacionesNIIFSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agrupaciones = [
                '3' => ['descripcion' => 'Efectivo y equivalentes al efectivo'],
                '4' => ['descripcion' => 'Inversiones'],
                '5' => ['descripcion' => 'Cuentas comerciales y otras cuentas por cobrar'],
                '6' => ['descripcion' => 'Inversiones no corriente'],
                '7' => ['descripcion' => 'Activos por impuestos corrientes'],
                '8' => ['descripcion' => 'Inventarios'],
                '9' => ['descripcion' => 'Anticipos y avances'],
                '10' => ['descripcion' => 'Otros activos'],
                '11' => ['descripcion' => 'Propiedades planta y equipos'],
                '12' => ['descripcion' => 'Activos Intangibles'],
                '13' => ['descripcion' => 'Impuesto diferido'],
                '14' => ['descripcion' => 'Obligaciones financieras'],
                '15' => ['descripcion' => 'Cuentas comerciales y otras cuentas por pagar'],
                '16' => ['descripcion' => 'Pasivos por Impuestos Corrientes'],
                '17' => ['descripcion' => 'Beneficios a empleados'],
                '18' => ['descripcion' => 'Anticipos y avances recibidos'],
                '19' => ['descripcion' => 'Otros Pasivos'],
                '20' => ['descripcion' => 'Obligaciones Financieras'],
                '21' => ['descripcion' => 'Cuentas por pagar comerciales y otras cuentas por pagar'],
                '22' => ['descripcion' => 'Pasivos Contingentes'],
                '23' => ['descripcion' => 'Pasivo por impuesto diferido'],
                '35' => ['descripcion' => 'Dividendos o participación'],
                '36' => ['descripcion' => 'Utilidad y/o perdidas del ejercicio'],
                '24' => ['descripcion' => 'Capital social'],
                '28' => ['descripcion' => 'Costos'],
                '29' => ['descripcion' => 'Gastos'],
                '30' => ['descripcion' => 'Gastos de impuestos de renta y cree'],
                '301'=> ['descripcion' => 'Hechos posteriores'],
                '31' => ['descripcion' => 'Nota'],
                '32' => ['descripcion' => 'Aprobación de los Estados Financieros '],
                '27' => ['descripcion' => 'Ingresos'],
                '40' => ['descripcion' => 'Otros pasivos no corrientes'],
                '41' => ['descripcion' => 'Bonos y papeles comerciales'],

        ];

         // 🔁 Datos por grupo NIIF
        $grupos = [
            3 => [
                '3' => 'Efectivo y equivalentes al efectivo',
                '4' => 'Inversiones',
                '5' => 'Cuentas comerciales y otras cuentas por cobrar',
                '6' => 'Inversiones no corriente',
                '7' => 'Activos por impuestos corrientes',
                '8' => 'Inventarios',
                '9' => 'Anticipos y avances',
                '10' => 'Otros activos',
                '11' => 'Propiedades planta y equipos',
                '12' => 'Activos Intangibles',
                '13' => 'Impuesto diferido',
                '14' => 'Obligaciones financieras',
                '15' => 'Cuentas comerciales y otras cuentas por pagar',
                '16' => 'Pasivos por Impuestos Corrientes',
                '17' => 'Beneficios a empleados',
                '18' => 'Anticipos y avances recibidos',
                '19' => 'Otros Pasivos',
                '20' => 'Obligaciones Financieras',
                '21' => 'Cuentas por pagar comerciales y otras cuentas por pagar',
                '22' => 'Pasivos Contingentes',
                '23' => 'Pasivo por impuesto diferido',
                '35' => 'Dividendos o participación',
                '36' => 'Utilidad y/o perdidas del ejercicio',
                '24' => 'Capital social',
                '28' => 'Costos',
                '29' => 'Gastos',
                '30' => 'Gastos de impuestos de renta y cree',
                '301'=> 'Hechos posteriores',
                '31' => 'Nota',
                '32' => 'Aprobación de los Estados Financieros ',
                '27' => 'Ingresos',
                '40' => 'Otros pasivos no corrientes',
                '41' => 'Bonos y papeles comerciales',
            ],
            2 => [
                '3' => 'Efectivos y Equivalentes De Efectivo',
                '4' => 'Cuentas Comerciales Y Otras Cuentas Por Cobrar',
                '5' => 'Activos Por Impuestos Corrientes',
                '6' => 'Activos No Financieros',
                '7' => 'Propiedad, Planta y Equipo ',
                '8' => 'Cuentas comerciales y otras cuentas por pagar',
                '9' => 'Impuesto de Renta',
                '10' => 'Pasivos por otros Impuestos',
                '11' => 'Pasivos No Financieros',
                '12' => 'Ingresos Actividades Ordinarias',
                '13' => 'Costos de Venta',
                '14' => 'Gastos de Administración',
                '15' => 'Otros Gastos Operacionales',
                '16' => 'Costos Financieros',
                '17' => 'Impuesto Régimen Simple',
                '18' => 'Transacciones con partes relacionadas',
                '19' => 'Aprobación de los estados financieros',
                '20' => 'Hechos ocurridos después del periodo sobre el que se informa',
                
            ],
        ];
         // 📩 Mensajes personalizados por grupo y código
        $mensajes = [
            3 => [
                '3' =>  'La empresa reconoce como efectivo y equivalentes al efectivo los recursos disponibles para su uso inmediato, incluyendo dinero en caja, depósitos bancarios a la vista y otras inversiones de alta liquidez con vencimientos inferiores a tres meses, que están sujetas a un riesgo insignificante de cambios en su valor. Estos activos se registran al valor nominal y su medición se realiza conforme a las normas contables aplicables, garantizando su disponibilidad para cumplir con obligaciones de corto plazo.',
                '4' =>  'La empresa reconoce como inversiones aquellos activos financieros adquiridos con el propósito de generar rendimientos, valorización del capital o participación en otras entidades. Se registran al costo o valor razonable, según corresponda, y pueden incluir inversiones en valores de renta fija o variable, participaciones en otras empresas y otros instrumentos financieros.',
                '5' =>  'La empresa reconoce como cuentas comerciales y otras cuentas por cobrar los derechos de cobro derivados de la venta de bienes, prestación de servicios u otros conceptos relacionados con la operación. Se registran al valor nominal y, cuando es aplicable, se ajustan por deterioro con base en estimaciones de pérdidas esperadas. Estas cuentas incluyen saldos por cobrar a clientes, anticipos otorgados y otros créditos exigibles. Su recuperación se monitorea de manera continua para garantizar una adecuada gestión del riesgo de crédito.',
                '6' =>  'La empresa reconoce como inversiones no corrientes aquellos activos financieros adquiridos con la intención de mantenerlos a largo plazo, ya sea para obtener rendimientos, apreciación de capital o participación en otras entidades. Estas pueden incluir participaciones en asociadas, inversiones en instrumentos de renta fija o variable, y otros valores financieros. Se miden al costo, valor razonable o método de participación, según corresponda, y se evalúan periódicamente para identificar deterioros que requieran ajustes en su valor contable.',
                '7' =>  'La empresa reconoce como activos por impuestos corrientes los saldos a favor resultantes de pagos anticipados, retenciones en la fuente o créditos fiscales generados en el período y que pueden ser utilizados para compensar obligaciones tributarias futuras. Estos activos se registran por el monto recuperable y se presentan en los estados financieros hasta su utilización o reembolso, de acuerdo con la normativa fiscal vigente',
                '8' =>  'La empresa reconoce los inventarios como activos destinados a la venta en el curso normal de las operaciones, en proceso de producción o como materiales y suministros para su consumo. Se miden al costo o al valor neto de realización, el menor de ambos, e incluyen costos directos de adquisición o producción, así como costos indirectos atribuibles. La metodología de valoración utilizada es el método de costo promedio.',
                '9' =>  'La empresa reconoce como anticipos y avances aquellos pagos efectuados antes de la recepción de bienes o la prestación de servicios. Estos incluyen anticipos a proveedores, adelantos a empleados y otras erogaciones que representen derechos exigibles en el futuro. Se registran como activos hasta que se devenguen o se cumplan las condiciones para su reclasificación a los rubros correspondientes.',
                '10' =>  'La empresa reconoce como otros activos aquellos recursos que no se clasifican dentro de las categorías de activos corrientes, propiedades, planta y equipo o activos intangibles, pero que generan beneficios económicos futuros. Estos pueden incluir depósitos en garantía, inversiones de largo plazo, anticipos otorgados y otros derechos adquiridos. Su reconocimiento y medición se realizan de acuerdo con su naturaleza y las normas contables aplicables, asegurando su adecuada presentación en los estados financieros.',
                '11' =>  'La empresa reconoce las propiedades, planta y equipo como activos tangibles adquiridos o construidos que se utilizan en la operación y cuya vida útil supera un año. Estos incluyen terrenos, edificaciones, maquinaria, equipos, mobiliario y vehículos. Se registran al costo de adquisición o construcción, incluyendo los costos directamente atribuibles a su puesta en condiciones de uso. Su depreciación se calcula de manera sistemática durante su vida útil estimada, y periódicamente se evalúa la existencia de deterioro para ajustar su valor en caso necesario, garantizando una presentación razonable en los estados financieros.',
                '12' =>  'La empresa reconoce como activos intangibles aquellos recursos identificables de naturaleza no monetaria y sin apariencia física, adquiridos o desarrollados internamente, que generan beneficios económicos futuros. Estos incluyen, entre otros, software, licencias, marcas, patentes, derechos de uso y desarrollos tecnológicos. Su reconocimiento se realiza al costo de adquisición o desarrollo, y su amortización se lleva a cabo de manera sistemática durante su vida útil estimada. Periódicamente, se evalúa la existencia de indicios de deterioro para ajustar su valor en caso de ser necesario.',
                '13' =>  'La empresa reconoce el activo por impuesto diferido cuando existen diferencias temporarias deducibles, pérdidas fiscales o créditos fiscales que pueden ser utilizados para reducir impuestos en períodos futuros. Este activo se calcula aplicando la tasa impositiva vigente sobre dichas diferencias y se reconoce en la medida en que sea probable su recuperación mediante ganancias fiscales futuras.',
                '14' =>  'La empresa reconoce como obligaciones financieras corrientes aquellos financiamientos con vencimiento a corto plazo, incluyendo préstamos bancarios, sobregiros y porciones de deuda a largo plazo con vencimiento dentro del siguiente año. Estas obligaciones se registran al valor nominal y pueden incluir intereses y otros costos asociados. Su medición y presentación reflejan los términos pactados con las entidades financieras, asegurando el adecuado cumplimiento de las obligaciones y la gestión del riesgo de liquidez.',
                '15' =>  'La empresa reconoce como cuentas comerciales y otras cuentas por pagar corrientes aquellas obligaciones con vencimiento en el corto plazo, generalmente dentro de un año. Estas incluyen deudas con proveedores por la adquisición de bienes y servicios, así como otros pasivos derivados de obligaciones laborales, impuestos por pagar, retenciones practicadas y compromisos adquiridos en el curso normal del negocio. Estas cuentas se registran al valor nominal y se liquidan conforme a los términos pactados con cada contraparte.',
                '16' =>  'Estos corresponden a las obligaciones tributarias derivadas del impuesto de renta y otras contribuciones, calculadas sobre la utilidad fiscal del período y pendientes de pago a la fecha de los estados financieros. Su liquidación se realiza dentro de los plazos establecidos por la legislación vigente.',
                '17' =>  'La empresa reconoce los beneficios a empleados conforme a las normas contables aplicables, incluyendo remuneraciones, prestaciones sociales, incentivos y otros beneficios otorgados en el curso normal de la relación laboral. Estos pueden clasificarse en beneficios a corto plazo, como sueldos, primas y vacaciones; beneficios a largo plazo, como indemnizaciones y planes de retiro; y beneficios pos{ANIO}empleo, como pensiones y otros aportes. Su reconocimiento y medición se realizan con base en los términos contractuales y la normatividad laboral vigente.',
                '18' =>  'La empresa reconoce los anticipos y avances recibidos como pasivos hasta el momento en que se cumplan las condiciones para su reconocimiento como ingreso, de acuerdo con las normas contables aplicables. Estos corresponden a pagos recibidos por parte de clientes u otras partes antes de la entrega de bienes o la prestación de servicios, y se presentan en los estados financieros hasta que se devenguen conforme a los términos contractuales establecidos.',
                '19' =>  'La empresa reconoce otros pasivos que no se clasifican dentro de las cuentas por pagar comerciales, obligaciones financieras o provisiones, de acuerdo con las normas contables aplicables. Estos incluyen obligaciones laborales, impuestos retenidos por pagar, anticipos recibidos, pasivos por contratos con clientes y otras responsabilidades adquiridas en el curso normal del negocio.',
                '20' =>  'La empresa reconoce como obligaciones financieras no corrientes aquellos financiamientos con vencimiento superior a un año, incluyendo préstamos bancarios, emisiones de deuda y otros instrumentos de financiamiento a largo plazo. Estas obligaciones se registran al valor del principal pendiente de pago, junto con los intereses y costos asociados. Su medición y presentación reflejan los términos pactados con las entidades financieras y otras fuentes de financiamiento, permitiendo una adecuada gestión del riesgo financiero y la planificación de la estructura de capital.',
                '21' =>  'La empresa reconoce como cuentas comerciales y otras cuentas por pagar no corrientes aquellas obligaciones con vencimiento superior a un año. Estas incluyen compromisos financieros de largo plazo con proveedores, acuerdos de pago diferido y otras obligaciones contractuales que no requieren liquidación inmediata. Estas cuentas se registran al valor nominal y su reconocimiento se realiza conforme a los términos pactados con cada contraparte, garantizando una adecuada gestión financiera y cumplimiento de las obligaciones a largo plazo.',
                '22' =>  'La empresa identifica y evalúa pasivos contingentes que pueden surgir de eventos pasados y cuya materialización depende de hechos futuros inciertos fuera del control de la entidad. Estos pasivos no se reconocen en los estados financieros.',
                '23' =>  'La empresa reconoce los saldos diferidos de acuerdo con las normas contables aplicables, incluyendo tanto ingresos diferidos, que corresponden a recursos recibidos anticipadamente por bienes o servicios que serán entregados en el futuro, como gastos diferidos, que representan erogaciones realizadas cuyo reconocimiento se distribuye en períodos futuros. Estos valores son amortizados sistemáticamente según la naturaleza de la transacción y su impacto en los resultados de la empresa.',
                '24' =>  'El capital social de la empresa está conformado por las aportaciones realizadas por los socios o accionistas, representadas en acciones o participaciones según la estructura legal de la entidad. Su monto y composición pueden estar sujetos a modificaciones conforme a decisiones de los órganos de gobierno de la empresa y a la normatividad vigente. Cualquier cambio en el capital social es debidamente registrado y revelado en los estados financieros, reflejando así la estructura de propiedad y el respaldo patrimonial de la entidad.',
                '35' =>  'La empresa reconoce los dividendos o la participación en el patrimonio como la distribución de utilidades a los accionistas o socios, aprobada por la asamblea general o el órgano competente. Estos pueden pagarse en efectivo, en acciones o mediante otros mecanismos establecidos en los estatutos de la empresa. Su reconocimiento se realiza en el momento en que se declara la distribución y se presenta como una disminución del patrimonio en los estados financieros, de acuerdo con las normas contables y legales aplicables.',
                '36' =>  'La empresa presenta el resultado del ejercicio conforme a las normas contables aplicables, diferenciando entre la utilidad o pérdida operacional, que corresponde al resultado generado por la actividad principal antes de considerar ingresos y gastos financieros, impuestos y otros rubros extraordinarios, y la utilidad o pérdida neta, que representa el resultado final del período después de aplicar todos los ingresos y gastos, incluidos los impuestos y otros ajustes. Adicionalmente, se incluyen otros resultados integrales que afectan el patrimonio, como revaluaciones, ajustes por conversión de moneda extranjera o cambios en instrumentos financieros. Estos resultados reflejan el desempeño financiero de la empresa y su capacidad para generar valor a lo largo del período contable.',
                '28' =>  'La empresa reconoce los costos asociados a sus operaciones conforme a las normas contables aplicables, asegurando su adecuada asignación según la naturaleza de la actividad. Estos incluyen costos de producción, adquisición de bienes o prestación de servicios, así como otros costos directos e indirectos relacionados con la operación. Su reconocimiento se realiza en el período en que se incurren, garantizando la correcta determinación del resultado del ejercicio.',
                '29' =>  'La empresa reconoce los gastos administrativos, de venta y financieros como erogaciones necesarias para el desarrollo de sus operaciones. Los gastos administrativos incluyen costos asociados a la gestión y operación interna, como salarios, arrendamientos y servicios. Los gastos de venta corresponden a aquellos relacionados con la comercialización y distribución de bienes o servicios, como publicidad, comisiones y logística. Los gastos financieros comprenden intereses, costos por financiamiento y diferencias en cambio. Estos gastos se registran en el período en que se incurren, conforme a las normas contables aplicables.',
                '30' =>  'La empresa reconoce el gasto por impuesto de renta o Régimen Simple de Tributación de acuerdo con las normas fiscales vigentes, incluyendo el impuesto corriente y el efecto del impuesto diferido sobre diferencias temporales.',
                '301'=>  'No se encontraron hechos posteriores a la fecha de cierre que afecten las cifras contenidas en los estados financieros.',
                '31' =>  'la nota queda en blanco para anotaciones o aclaraciones puntuales',
                '32' =>  'Los estados financieros y las notas que los acompañan fueron aprobados por el representante legal.',
                '27' =>  'La empresa reconoce sus ingresos de acuerdo con las normas contables aplicables, distinguiendo entre ingresos operacionales provenientes de su actividad principal y otros ingresos derivados de actividades secundarias.',
                '40' =>  'La empresa presenta en esta cuenta aquellos compromisos financieros y obligaciones que no se espera liquidar en el curso normal del ciclo operativo o dentro de los doce meses siguientes a la fecha del estado de situación financiera. Estos incluyen pasivos relacionados con provisiones, obligaciones laborales a largo plazo, pasivos por contratos de arrendamiento, anticipos de clientes a largo plazo, entre otros. Dichos pasivos se reconocen inicialmente por su valor razonable y posteriormente se miden al costo amortizado, según corresponda.',
                '41' =>  'Esta cuenta agrupa las emisiones de bonos, pagarés y otros instrumentos de deuda emitidos por la empresa para financiar sus operaciones, con vencimientos generalmente superiores a un año. Estos instrumentos representan obligaciones con terceros y son reconocidos inicialmente por el valor recibido, ajustado por los costos de transacción directamente atribuibles, y posteriormente se miden al costo amortizado. La empresa cumple con los términos contractuales de dichos instrumentos y registra los intereses devengados conforme a la tasa efectiva.',
            ],
            2 => [
                '3' => 'El detalle del efectivo y equivalentes de efectivo se detalla a continuación: {SALTO_PAGINA} Los saldos que conforman el Efectivo Y Equivalente De Efectivo no tienen ninguna restricción.',
                '4' => 'Las cuentas comerciales y otras cuentas por cobrar están compuestas por cuentas por cobrar a terceros',
                '5' => 'Los activos por impuestos corrientes están compuestos por el saldo a favor de exceso de IVA correspondiente al año {ANIO} y el saldo de retención en la fuente para pago del mismo periodo.',
                '6' => 'los activos financieros están compuestos por los anticipos a proveedores',
                '7' => 'El detalle de la propiedad planta y equipo y La variación es la siguiente: {SALTO_PAGINA}',
                '8' => 'A continuación, se refleja el detalle de las cuentas comerciales y otras cuentas por pagar',
                '9' => 'A partir de mayo del año 2024 {EMPRESA} cambia del al régimen simple de tributación, a régimen ordinario, el cual se reconoce el gasto RST en el bimestre (enero- febrero) y el gasto de renta de enero a mayo descontando lo causado en el primer bimestre en RST.',
                '10' => 'A continuacion el detalle de los pasivos por otros impuestos: {SALTO_PAGINA} los saldos reflejados corresponde a lo adeudado a corte de {FECHA_ACTUAL}, todos los impuestos liquidados en cada periodo han sido efectivamente pagados.',
                '11' => 'Se evidencia el saldo correspondiente al anticipo recibido por la señora Esther Julian Mejia Henao. {SALTO_PAGINA} El saldo correspondiente al contrato de Mandato que se tiene con Arprok correspondiente  al contrato de Parke 22.',
                '12' => 'Los ingresos son los incrementos de beneficios económicos durante el periodo, representados en incrementos de los activos o disminución de los pasivos, y no están relacionados con aportes de los socios ni los ingresos que desempeña {EMPRESA}.',
                '13' => 'Los costos de venta corresponden a servicios asociados a la actividad principal',
                '14' => 'A continuación el   detalle de los gastos de administración.',
                '15' => 'A continuación el detalle de los otros gastos operacionales.',
                '16' => 'A continuación el detalle de los costos financieros.',
                '17' => 'Para el presente año en el mes de mayo {EMPRESA} se traslada Régimen Simple De Tributación Simple, a Régimen Ordinario, en donde se refleja la causación de impuesto régimen simple de tributación simple correspondiente al mes de enero - febrero, y la causación de impuesto de renta correspondiente a los siguientes periodos.',
                '18' => 'El Gerente de la compañía tuvo una remuneración asignada a título de salarios y su valor consolidado del semestre ascendió a $221.920 millones. Se tiene suscrito 2 contratos de arrendamientos, por los cuales se pagó durante el primer semestre del año 2023 la suma de $15,640 millones de pesos y se presentan saldos pendientes por pagar a la Gerencia por valor de $9,813 millones de pesos  por arrendamientos y otros gastos.',
                '19' => 'Estos estados financieros fueron autorizados por la gerencia para su publicación el {FECHA_ACTUAL}.',
                '20' => 'No se presentaron hechos relevantes después del cierre de los estados financieros y hasta la fecha de su aprobación que puedan afectar de manera significativa la situación financiera de la empresa. {SALTO_PAGINA} Hasta aquí las notas y Revelaciones de los Estados Financieros de {EMPRESA}.',

            ],
        ];
         // 🔁 Insertar datos
        foreach ($grupos as $gruponiif => $agrupaciones) {
            foreach ($agrupaciones as $codigo => $descripcion) {
                AgrupacionesNIIF::updateOrCreate(
                    ['codigo' => $codigo, 'gruponiif' => $gruponiif],
                    [
                        'descripcion' => $descripcion,
                        'mensaje' => $mensajes[$gruponiif][$codigo] ?? null,
                    ]
                );
            }
        }
    }
}
