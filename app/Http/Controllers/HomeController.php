<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            DB::raw('MONTH(payment_date) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->whereYear('payment_date', date('Y'))
            ->where('status', 'Completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::create()->month($item->month)->format('M'),
                    'total' => $item->total
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
