@extends('layouts.app')

@section('title', 'Pagos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Pagos</h1>
        <div>
            <a href="{{ route('payments.pending') }}" class="btn btn-warning mr-2">
                <i class="fas fa-clock mr-1"></i> Pagos Pendientes
            </a>
            <a href="{{ route('payments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Registrar Pago
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="payments-table" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Atleta</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Referencia</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->athlete->full_name }}</td>
                                <td>${{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td>
                                    @switch($payment->payment_type)
                                        @case('Monthly_Fee')
                                            Mensualidad
                                            @break
                                        @case('Event_Registration')
                                            Evento
                                            @break
                                        @case('Equipment')
                                            Equipo
                                            @break
                                        @default
                                            {{ $payment->payment_type }}
                                    @endswitch
                                </td>
                                <td>
                                    @switch($payment->payment_method)
                                        @case('Transfer')
                                            Transferencia
                                            @break
                                        @case('Card')
                                            Tarjeta
                                            @break
                                        @case('Cash')
                                            Efectivo
                                            @break
                                        @default
                                            {{ $payment->payment_method }}
                                    @endswitch
                                </td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $payment->status === 'Completed' ? 'success' : 
                                        ($payment->status === 'Pending' ? 'warning' : 'danger') 
                                    }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('payments.show', $payment->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($payment->status === 'Pending')
                                            <form action="{{ route('payments.approve-single', $payment->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-success" 
                                                        title="Aprobar pago"
                                                        onclick="return confirm('¿Está seguro de aprobar este pago?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/buttons.bootstrap4.min.css')}}">
@stop

@section('js')
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/jszip.min.js')}}"></script>
    <script src="{{asset('js/pdfmake.min.js')}}"></script>
    <script src="{{asset('js/vfs_fonts.js')}}"></script>
    <script src="{{asset('js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('js/buttons.print.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('#payments-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel mr-1"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf mr-1"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print mr-1"></i> Imprimir',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    }
                ],
                order: [[2, 'desc']],
                columnDefs: [
                    {
                        targets: [-1],
                        orderable: false
                    }
                ]
            });
        });

        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    </script>
@stop