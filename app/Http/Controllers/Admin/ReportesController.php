<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Exports\ReportePuntosExport;
use App\Exports\ReporteCanjesExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportesController extends Controller
{
    public function exportarPuntos(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        return Excel::download(new ReportePuntosExport($fechaInicio, $fechaFin), 'reporte-puntos.xlsx');
    }

    public function exportarCanjes(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        return Excel::download(new ReporteCanjesExport($fechaInicio, $fechaFin), 'reporte-canjes.xlsx');
    }
}
