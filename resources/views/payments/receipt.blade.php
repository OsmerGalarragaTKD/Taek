<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recibo de Pago</h1>
            <p>Fecha de emisión: {{ now()->format('d/m/Y') }}</p>
        </div>

        <div class="details">
            <p><strong>Recibo N°:</strong> {{ $payment->id }}</p>
            <p><strong>Atleta:</strong> {{ $payment->athlete->full_name }}</p>
            <p><strong>Fecha de Pago:</strong> {{ $payment->payment_date->format('d/m/Y') }}</p>
            <p><strong>Método de Pago:</strong> {{ $payment->payment_method }}</p>
            @if($payment->reference_number)
                <p><strong>Número de Referencia:</strong> {{ $payment->reference_number }}</p>
            @endif
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        @if($payment->payment_type === 'Monthly_Fee')
                        Mensualidad - {{ $payment->month ? $payment->month->format('m Y') : 'N/A' }}
                        @elseif($payment->payment_type === 'Event_Registration')
                            Inscripción - {{ $payment->event->name }}
                        @endif
                    </td>
                    <td>${{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <td>${{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>Gracias por su pago.</p>
        </div>
    </div>
</body>
</html>

