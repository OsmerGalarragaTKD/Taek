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

        <!-- Only show the Generate Receipt button if payment status is Completed -->
        @if(in_array($pago->payment_type, ['Monthly_Fee', 'Event_Registration']) && $pago->status === 'Completed')
            <a href="{{ route('payments.receipt', $pago->id) }}" class="btn btn-info btn-block mb-3">
                <i class="fas fa-file-pdf mr-1"></i>
                Generar Recibo
            </a>
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