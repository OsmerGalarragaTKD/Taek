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
                    <dd>{{ __($pago->payment_type) }}</dd>

                    <dt>Método de Pago</dt>
                    <dd>{{ __($pago->payment_method) }}</dd>

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

