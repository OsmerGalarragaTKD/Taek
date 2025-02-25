@if($pago->receipt_url)
    <div class="card mt-4">
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

