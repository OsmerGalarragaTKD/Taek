@extends('adminlte::page')

@section('title', 'Detalle de Pago')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalle del Pago #{{ $pago->id }}</h1>
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
        <div class="alert alert-success alert-dismissible fade show" id="success-alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            @include('payments.partials.payment_info')
            @include('payments.partials.receipt_image')
        </div>

        <div class="col-md-4">
            @include('payments.partials.actions')
        </div>
    </div>

    @include('payments.partials.edit_modal')
@stop

@section('js')
    <script src="{{ asset('js/bs-custom-file-input.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();

            $('#receipt_url').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });

            setTimeout(function() {
                $('#success-alert').alert('close');
            }, 5000);
        });
    </script>
@stop

