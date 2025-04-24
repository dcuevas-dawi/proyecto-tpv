<!-- Ticket html for printing a finished order -->

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

            .iva {
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
            <h1>
                @if (Auth::user()->stablishmentDetails && Auth::user()->stablishmentDetails->commercial_name)
                    {{ Auth::user()->stablishmentDetails->commercial_name }}
                @elseif (Auth::user()->stablishmentDetails)
                    {{ Auth::user()->stablishmentDetails->legal_name }}
                @else
                    Ticket de compra
                @endif
            </h1>
        </div>

        <div class="company-info">
            @if(Auth::user()->stablishmentDetails && Auth::user()->stablishmentDetails->legal_name)
                <p>{{ Auth::user()->stablishmentDetails->legal_name }}</p>
            @endif
            @if(Auth::user()->stablishmentDetails && Auth::user()->stablishmentDetails->cif)
                <p>CIF: {{ Auth::user()->stablishmentDetails->cif }}</p>
            @endif
            @if(Auth::user()->stablishmentDetails && Auth::user()->stablishmentDetails->address)
                <p>{{ Auth::user()->stablishmentDetails->address }}</p>
            @endif
            @if(Auth::user()->stablishmentDetails && (Auth::user()->stablishmentDetails->postal_code || Auth::user()->stablishmentDetails->city || Auth::user()->stablishmentDetails->province))
                <p>
                @if(Auth::user()->stablishmentDetails->postal_code)
                    {{ Auth::user()->stablishmentDetails->postal_code }}
                @endif
                @if(Auth::user()->stablishmentDetails->city)
                    {{ Auth::user()->stablishmentDetails->city }}
                @endif
                @if(Auth::user()->stablishmentDetails->province)
                    {{ Auth::user()->stablishmentDetails->province }}
                @endif
                </p>
            @endif
            @if(Auth::user()->stablishmentDetails && Auth::user()->stablishmentDetails->phone)
                <p>Tel: {{ Auth::user()->stablishmentDetails->phone }}</p>
            @endif
            @if(Auth::user()->stablishmentDetails && Auth::user()->stablishmentDetails->email)
                <p>{{ Auth::user()->stablishmentDetails->email }}</p>
            @endif
        </div>

        <div class="ticket-info">
            <p>FACTURA SIMPLIFICADA: FS-{{ date('Y') }}-{{ $order->order_id }}</p>
            <p>Mesa: {{ $order->table->number }}</p>
            <p>Fecha: {{ $order->updated_at->format('d/m/Y H:i') }}</p>
            <p>Pedido: #{{ $order->order_id }}</p>
            <p>Le atendió: {{ (session('employee_name')) }}</p>
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

        <table class="iva">
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
