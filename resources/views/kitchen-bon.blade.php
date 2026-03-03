<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bon #{{ $order['order_number'] }}</title>
    <style>
        @page { margin: 0; size: 80mm auto; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 80mm;
            padding: 4mm;
            color: #000;
        }
        .center { text-align: center; }
        .bold   { font-weight: bold; }
        .large  { font-size: 16px; }
        .xlarge { font-size: 20px; }
        hr      { border: none; border-top: 1px dashed #000; margin: 4px 0; }
        .row    { display: flex; justify-content: space-between; }
        .items  { margin: 4px 0; }
        .item   { display: flex; justify-content: space-between; margin: 2px 0; }
        .qty    { font-weight: bold; min-width: 20px; }
    </style>
</head>
<body>
    <div class="center bold xlarge" style="margin-bottom: 4px;">{{ config('app.name', 'Restaurant') }}</div>
    <div class="center" style="margin-bottom: 6px; font-size: 10px;">Bon afdruk</div>

    <hr>

    <div class="center bold large" style="margin: 4px 0;">
        #{{ $order['order_number'] }}
    </div>

    <div class="center" style="margin-bottom: 4px; font-size: 11px;">
        {{ $order['created_at'] }}
        &nbsp;&bull;&nbsp;
        {{ $order['type'] === 'delivery' ? 'BEZORGING' : 'AFHALEN' }}
    </div>

    <hr>

    <div class="bold" style="margin: 4px 0;">{{ $order['customer_name'] }}</div>
    <div>Tel: {{ $order['customer_phone'] }}</div>
    @if($order['type'] === 'delivery' && $order['delivery_address'])
        <div style="margin-top: 2px;">Adres: {{ $order['delivery_address'] }}</div>
    @endif

    <hr>

    <div class="items">
        @foreach($order['items'] as $item)
            <div class="item">
                <span><span class="qty">{{ $item['quantity'] }}x</span> {{ $item['name'] }}</span>
            </div>
        @endforeach
    </div>

    @if($order['notes'])
        <hr>
        <div class="bold" style="margin-top: 2px;">Opmerking:</div>
        <div>{{ $order['notes'] }}</div>
    @endif

    <hr>
    <div class="center" style="font-size: 10px; margin-top: 4px;">Bedankt voor uw bestelling!</div>

    <script>window.onload = function(){ window.print(); }</script>
</body>
</html>
