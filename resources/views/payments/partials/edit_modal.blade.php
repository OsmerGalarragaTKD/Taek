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

