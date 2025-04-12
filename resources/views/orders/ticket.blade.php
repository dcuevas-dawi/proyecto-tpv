<!-- resources/views/orders/ticket.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket de Compra</title>
    <script src="{{ asset('js/ticket.js') }}"></script>
    <style>
        @page {
            width: 80mm;
            margin: 0;
        }

        html, body {
            display: block;
            width: 80mm;
            height: fit-content;
            margin: 0;
            padding: 10px;
            overflow: hidden ;
        }

        header, footer, nav, aside {
            display: none;
        }

        .ticket-info {
            margin-bottom: 10px;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px;
        }

        .product-list {
            width: 100%;
        }

        .product-list tr td {
            padding: 3px 0;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        .company-info {
            text-align: center;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .iva-desglose {
            margin-top: 10px;
            width: 100%;
            font-size: 12px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>{{ config('app.name', 'Restaurante') }}</h1>
</div>

<div class="company-info">
    <p><strong>RESTAURANTE EJEMPLO, S.L.</strong></p>
    <p>CIF: B12345678</p>
    <p>C/ Gran Vía, 123</p>
    <p>28001 Madrid</p>
    <p>Tel: 912 345 678</p>
    <p>info@restauranteejemplo.com</p>
</div>

<div class="ticket-info">
    <p>FACTURA SIMPLIFICADA: FS-{{ date('Y') }}-{{ $order->id }}</p>
    <p>Mesa: {{ $order->table->number }}</p>
    <p>Fecha: {{ $order->updated_at->format('d/m/Y H:i') }}</p>
    <p>Pedido: #{{ $order->id }}</p>
    <p>Le atendió: {{ $order->employee->name ?? 'Carlos García' }}</p>
</div>

<table class="product-list">
    <tr>
        <th align="left">Producto</th>
        <th align="center">Cant.</th>
        <th align="right">Precio</th>
        <th align="right">Total</th>
    </tr>
    @foreach ($order->products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td align="center">{{ $product->pivot->quantity }}</td>
            <td align="right">{{ number_format($product->pivot->price_at_time, 2) }}€</td>
            <td align="right">{{ number_format($product->pivot->quantity * $product->pivot->price_at_time, 2) }}€</td>
        </tr>
    @endforeach
</table>

@php
    $subtotal = $order->products->sum(function($product) {
        return $product->pivot->quantity * $product->pivot->price_at_time;
    });
    $iva = $subtotal * 0.21;
    $baseImponible = $subtotal - $iva;
@endphp

<table class="iva-desglose">
    <tr>
        <td align="left">Base imponible</td>
        <td align="right">{{ number_format($baseImponible, 2) }}€</td>
    </tr>
    <tr>
        <td align="left">IVA (21%)</td>
        <td align="right">{{ number_format($iva, 2) }}€</td>
    </tr>
</table>

<div class="total">
    TOTAL: {{ number_format($subtotal, 2) }}€
</div>

<div class="footer">
    <p>Gracias por su visita</p>
</div>
</body>
</html>
