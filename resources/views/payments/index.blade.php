@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">
                                    <i class="bi bi-cash me-2"></i>
                                    Listado de Pagos
                                </h5>
                            </div>
                            <div class="col text-end">
                                <a href="{{ route('payments.pending') }}" class="btn btn-warning me-2">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Pagos Pendientes
                                </a>
                                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Nuevo Pago
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="paymentsTable" class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="px-4">Atleta</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Método</th>
                                        <th scope="col" class="text-center">Estado</th>
                                        <th scope="col" class="text-end px-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td class="px-4">{{ $payment->athlete->full_name ?? 'N/A' }}</td>
                                            <td>${{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                            <td>{{ $payment->payment_type }}</td>
                                            <td>{{ $payment->payment_method }}</td>
                                            <td class="text-center">
                                                @switch($payment->status)
                                                    @case('Planned')
                                                        <span class="badge bg-warning">Planificado</span>
                                                    @break

                                                    @case('Active')
                                                        <span class="badge bg-primary">Activo</span>
                                                    @break

                                                    @case('Completed')
                                                        <span class="badge bg-success">Completado</span>
                                                    @break

                                                    @case('Cancelled')
                                                        <span class="badge bg-danger">Cancelado</span>
                                                    @break

                                                    @default
                                                        <span class="badge bg-secondary">{{ $payment->status }}</span>
                                                @endswitch
                                            </td>
                                            <td class="text-end px-4">
                                                @if ($payment->status === 'Pending')
                                                    <form action="{{ route('payments.approve.single', $payment->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success me-2"
                                                            title="Aprobar Pago">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('payments.show', $payment->id) }}"
                                                    class="btn btn-sm btn-info me-2">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('¿Estás seguro de eliminar este pago?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                                        <p class="mb-0 mt-2">No hay pagos registrados</p>
                                                        <a href="{{ route('payments.create') }}"
                                                            class="btn btn-sm btn-primary mt-3">
                                                            <i class="bi bi-plus-circle me-1"></i>
                                                            Crear Nuevo Pago
                                                        </a>
                                                    </div>
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
        </div>

        @push('css')
            <style>
                .table> :not(caption)>*>* {
                    padding: 1rem 0.5rem;
                }

                .pagination {
                    margin-bottom: 0;
                }
            </style>
        @endpush

        @push('js')
            <script>
                $(document).ready(function() {
                    $('#paymentsTable').DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                        },
                        "responsive": true,
                        "autoWidth": false,
                        "order": [
                            [2, 'desc']
                        ],
                        "pageLength": 10,
                        "columnDefs": [{
                            "targets": -1,
                            "orderable": false
                        }]
                    });

                    // Auto-cerrar alertas después de 5 segundos
                    window.setTimeout(function() {
                        document.querySelectorAll(".alert").forEach(function(alert) {
                            var bsAlert = new bootstrap.Alert(alert);
                            setTimeout(function() {
                                bsAlert.close();
                            }, 5000);
                        });
                    }, 1000);
                });
            </script>
        @endpush
    @endsection
