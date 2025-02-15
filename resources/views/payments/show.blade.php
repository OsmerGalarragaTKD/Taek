@extends('adminlte::page')

@section('title', 'Detalle de Pago')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalle del Pago</h1>
        <div>
            @if($pago->status === 'Pending')
                <form action="{{ route('payments.approve-single', $pago->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success mr-2" onclick="return confirm('¿Está seguro de aprobar este pago?')">
                        <i class="fas fa-check mr-1"></i> Aprobar Pago
                    </button>
                </form>
            @endif
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Volver
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

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información del Pago</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <dl>
                                <dt>Atleta</dt>
                                <dd>{{ $pago->athlete->full_name }}</dd>

                                <dt>Monto</dt>
                                <dd>${{ number_format($pago->amount, 2) }}</dd>

                                <dt>Fecha de Pago</dt>
                                <dd>{{ $pago->payment_date->format('d/m/Y') }}</dd>

                                <dt>Estado</dt>
                                <dd>
                                    <span class="badge badge-{{ 
                                        $pago->status === 'Completed' ? 'success' : 
                                        ($pago->status === 'Pending' ? 'warning' : 'danger') 
                                    }}">
                                        {{ $pago->status }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-sm-6">
                            <dl>
                                <dt>Tipo de Pago</dt>
                                <dd>
                                    @switch($pago->payment_type)
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
                                            {{ $pago->payment_type }}
                                    @endswitch
                                </dd>

                                <dt>Método de Pago</dt>
                                <dd>
                                    @switch($pago->payment_method)
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
                                            {{ $pago->payment_method }}
                                    @endswitch
                                </dd>

                                <dt>Número de Referencia</dt>
                                <dd>{{ $pago->reference_number ?? 'N/A' }}</dd>

                                @if($pago->completed_at)
                                    <dt>Fecha de Aprobación</dt>
                                    <dd>{{ $pago->completed_at->format('d/m/Y H:i:s') }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>

                    @if($pago->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <dt>Notas</dt>
                                <dd>{{ $pago->notes }}</dd>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($pago->receipt_url)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Comprobante de Pago</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ Storage::url($pago->receipt_url) }}" 
                                 alt="Comprobante de pago" 
                                 class="img-fluid rounded">
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ Storage::url($pago->receipt_url) }}" 
                               class="btn btn-info" 
                               target="_blank">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                Ver Imagen Completa
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Acciones</h3>
                </div>
                <div class="card-body">
                    <button type="button" 
                            class="btn btn-warning btn-block mb-3" 
                            data-toggle="modal" 
                            data-target="#editPaymentModal">
                        <i class="fas fa-edit mr-1"></i>
                        Editar Pago
                    </button>

                    @if($pago->status === 'Pending')
                        <form action="{{ route('payments.approve-single', $pago->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="btn btn-success btn-block mb-3"
                                    onclick="return confirm('¿Está seguro de aprobar este pago?')">
                                <i class="fas fa-check mr-1"></i>
                                Aprobar Pago
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('payments.destroy', $pago->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-danger btn-block"
                                onclick="return confirm('¿Está seguro de eliminar este pago? Esta acción no se puede deshacer.')">
                            <i class="fas fa-trash mr-1"></i>
                            Eliminar Pago
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentModalLabel">Editar Pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('payments.update', $pago->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Monto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               class="form-control" 
                                               id="amount" 
                                               name="amount" 
                                               value="{{ $pago->amount }}" 
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_date">Fecha de Pago <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="payment_date" 
                                           name="payment_date" 
                                           value="{{ $pago->payment_date->format('Y-m-d') }}" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_type">Tipo de Pago <span class="text-danger">*</span></label>
                                    <select class="form-control" id="payment_type" name="payment_type" required>
                                        <option value="Monthly_Fee" {{ $pago->payment_type == 'Monthly_Fee' ? 'selected' : '' }}>
                                            Mensualidad
                                        </option>
                                        <option value="Event_Registration" {{ $pago->payment_type == 'Event_Registration' ? 'selected' : '' }}>
                                            Evento
                                        </option>
                                        <option value="Equipment" {{ $pago->payment_type == 'Equipment' ? 'selected' : '' }}>
                                            Equipo
                                        </option>
                                        <option value="Other" {{ $pago->payment_type == 'Other' ? 'selected' : '' }}>
                                            Otro
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method">Método de Pago <span class="text-danger">*</span></label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="Cash" {{ $pago->payment_method == 'Cash' ? 'selected' : '' }}>
                                            Efectivo
                                        </option>
                                        <option value="Transfer" {{ $pago->payment_method == 'Transfer' ? 'selected' : '' }}>
                                            Transferencia
                                        </option>
                                        <option value="Card" {{ $pago->payment_method == 'Card' ? 'selected' : '' }}>
                                            Tarjeta
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reference_number">Número de Referencia</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="reference_number" 
                                   name="reference_number" 
                                   value="{{ $pago->reference_number }}">
                        </div>

                        <div class="form-group">
                            <label for="status">Estado <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Pending" {{ $pago->status == 'Pending' ? 'selected' : '' }}>
                                    Pendiente
                                </option>
                                <option value="Completed" {{ $pago->status == 'Completed' ? 'selected' : '' }}>
                                    Completado
                                </option>
                                <option value="Cancelled" {{ $pago->status == 'Cancelled' ? 'selected' : '' }}>
                                    Cancelado
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notas</label>
                            <textarea class="form-control" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3">{{ $pago->notes }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="receipt_url">Nuevo Comprobante</label>
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input" 
                                       id="receipt_url" 
                                       name="receipt_url" 
                                       accept="image/*">
                                <label class="custom-file-label" for="receipt_url">Seleccionar archivo...</label>
                            </div>
                            <small class="form-text text-muted">
                                Dejar en blanco para mantener el comprobante actual
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{asset('js/bs-custom-file-input.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();

            // Mostrar el nombre del archivo seleccionado
            $('#receipt_url').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });
        });

        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    </script>
@stop