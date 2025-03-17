<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Estadísticas de Atletas
        $totalAthletes = Athlete::count();
        $activeAthletes = Athlete::where('status', 'Active')->count();
        $inactiveAthletes = Athlete::where('status', 'Inactive')->count();

        // Estadísticas de Eventos
        $activeEvents = Event::whereIn('status', ['Planned', 'Active'])->count();
        $completedEvents = Event::where('status', 'Completed')->count();
        $upcomingEvents = Event::where('start_date', '>', now())
            ->where('status', 'Planned')
            ->orderBy('start_date')
            ->take(5)
            ->get();

        // Estadísticas de Sedes
        $totalVenues = Venue::count();
        $activeVenues = Venue::where('status', 'Active')->count();

        // Ingresos Mensuales del Año Actual
        $monthlyIncomes = Payment::select(
            DB::raw('YEAR(payment_date) as year'), // Extraer el año
            DB::raw('MONTH(payment_date) as mes'), // Extraer el mes
            DB::raw('SUM(amount) as total') // Sumar los montos
        )
            ->whereYear('payment_date', date('Y')) // Filtrar por el año actual
            ->where('status', 'Completed') // Filtrar por pagos completados
            ->groupBy('year', 'mes') // Agrupar por año y mes
            ->orderBy('year') // Ordenar por año
            ->orderBy('mes') // Ordenar por mes
            ->get()
            ->map(function ($item) {
                // Crear una fecha con el año y mes extraídos
                $date = Carbon::create($item->year, $item->month, 1);

                return [
                    'month' => $date->format('M Y'), // Formatear como "Mes Año" (ej. "Jan 2024")
                    'total' => $item->total // Mantener el total
                ];
            });

        // Últimos Pagos
        $recentPayments = Payment::with('athlete')
            ->orderBy('payment_date', 'desc')
            ->take(5)
            ->get();

        return view('home', compact(
            'totalAthletes',
            'activeAthletes',
            'inactiveAthletes',
            'activeEvents',
            'completedEvents',
            'upcomingEvents',
            'totalVenues',
            'activeVenues',
            'monthlyIncomes',
            'recentPayments'
        ));
    }
}
