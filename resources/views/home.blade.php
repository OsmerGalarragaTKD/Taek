@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        @role('Super Admin')
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <form action="{{ route('backup.create') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Crear Backup
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                {{--   @if (session('output'))
                    <div class="debug-output">
                        <h4>Detalles técnicos:</h4>
                        <pre>{{ session('output') }}</pre>
                    </div>
                @endif --}}
            @endif
        @endrole
        <!-- Estadísticas Generales -->

        <div class="row g-3 mb-4">
            @role('Super Admin')
                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded">
                                        <i class="bi bi-people text-primary" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Total Atletas</h6>
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0 me-2">{{ $totalAthletes }}</h4>
                                        <small class="text-success">
                                            <i class="bi bi-person-check-fill"></i> {{ $activeAthletes }} activos
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endrole



            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm bg-success bg-opacity-10 rounded">
                                    <i class="bi bi-calendar-event text-success" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Eventos Activos</h6>
                                <div class="d-flex align-items-center">
                                    <h4 class="mb-0 me-2">{{ $activeEvents }}</h4>
                                    <small class="text-muted">
                                        <i class="bi bi-check-circle-fill"></i> {{ $completedEvents }} completados
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @role('Super Admin')
                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm bg-info bg-opacity-10 rounded">
                                        <i class="bi bi-building text-info" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Total Sedes</h6>
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0 me-2">{{ $totalVenues }}</h4>
                                        <small class="text-success">
                                            <i class="bi bi-check-circle-fill"></i> {{ $activeVenues }} activas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endrole

            @role('Super Admin')
                <div class="col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm bg-warning bg-opacity-10 rounded">
                                        <i class="bi bi-cash text-warning" style="font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Ingresos del Mes</h6>
                                    <div class="d-flex align-items-center">
                                        <h4 class="mb-0">${{ number_format($monthlyIncomes->last()['total'] ?? 0, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endrole

        </div>

        <div class="row g-4">
            <!-- Gráfica de Ingresos -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">Ingresos Mensuales {{ date('Y') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Próximos Eventos -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">Próximos Eventos</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($upcomingEvents as $event)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $event->name }}</h6>
                                        <small class="text-muted">{{ $event->start_date->format('d/m/Y') }}</small>
                                    </div>
                                    <p class="mb-1 text-muted small">{{ str_replace('_', ' ', $event->type) }}</p>
                                </div>
                            @empty
                                <div class="list-group-item text-center py-4">
                                    <i class="bi bi-calendar-x text-muted d-block mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">No hay eventos próximos</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>


            @role('Super Admin')
                <!-- Últimos Pagos -->
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <div class="row aling-items-center">
                                <div class="col">
                                    <h6 class="mb-0">Últimos Pagos Registrados</h6>
                                </div>
                                <div class="col text-end">
                                    <a href="{{ route('payments.pending') }}" class="btn btn-warning me-2">
                                        <i class="bi bi-clock-history me-1"></i>
                                        Pagos Pendientes
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Atleta</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                            <th>Tipo</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentPayments as $payment)
                                            <tr>
                                                <td>{{ $payment->athlete->full_name }}</td>
                                                <td>${{ number_format($payment->amount, 2) }}</td>
                                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                                <td>{{ $payment->payment_type }}</td>
                                                <td>
                                                    @switch($payment->status)
                                                        @case('Completed')
                                                            <span class="badge bg-success">Completado</span>
                                                        @break

                                                        @case('Pending')
                                                            <span class="badge bg-warning">Pendiente</span>
                                                        @break

                                                        @default
                                                            <span class="badge bg-secondary">{{ $payment->status }}</span>
                                                    @endswitch
                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">
                                                        <i class="bi bi-inbox text-muted d-block mb-2"
                                                            style="font-size: 2rem;"></i>
                                                        <p class="mb-0">No hay pagos registrados</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endrole
        </div>

        @push('css')
            <link rel="stylesheet" href="{{ asset('icons/bootstrap-icons.css') }}">

            <style>
                .avatar-sm {
                    width: 3rem;
                    height: 3rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
            </style>
        @endpush

        @push('js')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Datos para la gráfica
                    const monthlyIncomes = @json($monthlyIncomes);

                    // Configuración de la gráfica
                    const ctx = document.getElementById('incomeChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: monthlyIncomes.map(item => item.month),
                            datasets: [{
                                label: 'Ingresos Mensuales',
                                data: monthlyIncomes.map(item => item.total),
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '$' + value.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @endpush
    @endsection
