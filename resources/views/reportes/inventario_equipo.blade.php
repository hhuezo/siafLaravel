<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Inventario de Mobiliario y Equipo</title>
    <style type="text/css">
        @page {
            margin: 100px 40px 140px 40px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        header {
            position: fixed;
            top: -90px;
            left: 0;
            right: 0;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -120px;
            left: 0;
            right: 0;
            height: 120px;
            text-align: center;
            font-size: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
        }

        .totales {
            text-align: right;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .firma {
            width: 45%;
            display: inline-block;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>

<body>

    @php
        $totalInicial = $equipos->sum('valor_inicial');
        $totalDepreciacion = $equipos->sum(fn($e) => $e->depresiacionAcumulada ?? 0);
        $totalActual = $equipos->sum(fn($e) => $e->valorActual ?? 0);
    @endphp

    <header>
        <h3>Instituto Salvadoreño de Transformación Agraria</h3>
        <h4>Sección de Activo Fijo</h4>
        <h4>Inventario Físico de Mobiliario y Equipo</h4>
    </header>

    <footer>
        {{-- <div class="totales">
            Subtotal Valor Inicial: ${{ number_format($totalInicial, 2) }}<br>
            Total Depreciación: ${{ number_format($totalDepreciacion, 2) }}<br>
            Subtotal Valor Actual: ${{ number_format($totalActual, 2) }}
        </div> --}}

        <div class="totales">
            <br><br><br>
        </div>

        <div class="firma">
            ___________________________________<br>
            <strong>Elaborado por:</strong><br>
            Nombre<br><br>
            Firma y sello
        </div>

        <div class="firma" style="float: right;">
            ___________________________________<br>
            <strong>Revisado por:</strong><br>
            Nombre<br><br>
            Firma y sello
        </div>

        <br clear="all">
        <p style="text-align:left; margin-top:10px;">
            {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
        </p>
    </footer>

    <main>
        <table>
            <thead>
                <tr>
                    <th style="width: 90px;">Código</th>
                    <th>Descripción</th>
                    <th>Cuenta Contable</th>
                    <th>Unidad</th>
                    <th>Ubicación</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Serie</th>
                    <th>Color</th>
                    <th>Estado</th>
                    <th>Fuente</th>
                    <th>Adq.</th>
                    <th>Valor Adq.</th>
                    <th>Valor Actual</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($equipos as $equipo)
                    <tr>
                        <td>{{ $equipo->codigo_activo }}</td>
                        <td>{{ $equipo->subclase->descripcion ?? '' }}</td>
                        <td>{{ $equipo->cuentaContable->codigo ?? '' }}</td>
                        <td>{{ $equipo->ambiente->unidad->descripcion ?? '' }}</td>
                        <td>{{ $equipo->ambiente->descripcion ?? '' }}</td>
                        <td>{{ $equipo->marca ?? '' }}</td>
                        <td>{{ $equipo->modelo ?? '' }}</td>
                        <td>{{ $equipo->serie ?? '' }}</td>
                        <td>{{ $equipo->color->descripcion ?? '' }}</td>
                        <td>{{ $equipo->estadoFisico->descripcion ?? '' }}</td>
                        <td>{{ $equipo->fuente->descripcion ?? '' }}</td>
                        <td>{{ \Carbon\Carbon::parse($equipo->fecha_adquisicion)->format('d/m/Y') }}</td>
                        <td>${{ number_format($equipo->valor_inicial, 2) }}</td>
                        <td>${{ number_format($equipo->valorActual ?? 0, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                {{-- <tr style="font-weight:bold;">
                    <td colspan="11" style="text-align: right;">Subtotal de activos por página:</td>
                    <td></td>
                    <td style="text-align: right;">${{ number_format($equipos->sum('valor_inicial'), 2) }}</td>
                    <td style="text-align: right;">
                        ${{ number_format($equipos->sum(fn($e) => $e->valorActual ?? 0), 2) }}</td>
                </tr> --}}
                <tr style="font-weight:bold;">
                    <td colspan="11" style="text-align: right;">Total activos:</td>
                    <td></td>
                    <td style="text-align: right;">${{ number_format($equipos->sum('valor_inicial'), 2) }}</td>
                    <td style="text-align: right;">
                        ${{ number_format($equipos->sum(fn($e) => $e->valorActual ?? 0), 2) }}</td>
                </tr>
            </tfoot>
        </table>

    </main>

</body>

</html>
