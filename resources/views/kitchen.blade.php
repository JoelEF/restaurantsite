<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuken Display</title>
    <style>
        body { font-family: monospace; background: #111; color: #fff; margin: 0; padding: 16px; }
        h1 { font-size: 1.2rem; margin: 0 0 12px; color: #f97316; }
        #status { font-size: 0.75rem; color: #aaa; margin-bottom: 16px; }
        .order { background: #1e1e1e; border: 2px solid #f97316; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
        .order-header { font-size: 1.1rem; font-weight: bold; margin-bottom: 8px; }
        .order-meta { font-size: 0.85rem; color: #ccc; margin-bottom: 8px; }
        .order-items { border-top: 1px dashed #555; padding-top: 8px; }
        .item { font-size: 1rem; margin-bottom: 4px; }
        .item span { font-size: 1.2rem; font-weight: bold; color: #f97316; }
        .badge { display:inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }
        .bezorging { background: #1d4ed8; }
        .afhalen   { background: #15803d; }
        .notes { margin-top: 8px; border-top: 1px dashed #555; padding-top: 8px; font-size: 0.85rem; color: #fbbf24; }

        @media print {
            body { background: #fff; color: #000; padding: 0; }
            #screen-ui { display: none; }
            .order { border: 2px dashed #000; color: #000; background: #fff; page-break-after: always; }
            .order-meta { color: #333; }
            .badge.bezorging { background: #93c5fd; color: #000; }
            .badge.afhalen   { background: #86efac; color: #000; }
            .notes { color: #78350f; }
        }
    </style>
</head>
<body>

<div id="screen-ui">
    <h1>🍽️ Keuken Display</h1>
    <div id="status">Laden...</div>
</div>

<div id="orders-container"></div>

<script>
    const POLL_INTERVAL = 20000; // 20 seconden
    const STORAGE_KEY   = 'printed_order_ids';

    function getPrinted() {
        return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    }

    function markPrinted(ids) {
        const existing = getPrinted();
        localStorage.setItem(STORAGE_KEY, JSON.stringify([...new Set([...existing, ...ids])]));
    }

    function renderOrders(orders) {
        const container = document.getElementById('orders-container');
        container.innerHTML = '';
        orders.forEach(order => {
            const div = document.createElement('div');
            div.className = 'order';
            div.innerHTML = `
                <div class="order-header">
                    #${order.order_number}
                    &nbsp;<span class="badge ${order.type}">${order.type === 'delivery' ? '🚚 BEZORGING' : '🏃 AFHALEN'}</span>
                    &nbsp;<small style="color:#aaa">${order.created_at}</small>
                </div>
                <div class="order-meta">
                    <strong>${order.customer_name}</strong> &nbsp;📞 ${order.customer_phone}
                    ${order.type === 'delivery' ? '<br>📍 ' + order.delivery_address : ''}
                </div>
                <div class="order-items">
                    ${order.items.map(i => `<div class="item"><span>${i.quantity}x</span> ${i.name}</div>`).join('')}
                </div>
                ${order.notes ? `<div class="notes">⚠️ ${order.notes}</div>` : ''}
            `;
            container.appendChild(div);
        });
    }

    async function poll() {
        try {
            const res = await fetch('{{ route("kitchen.orders") }}');
            const orders = await res.json();

            const printed = getPrinted();
            const newOrders = orders.filter(o => !printed.includes(o.id));

            // Altijd alle bestellingen tonen op scherm
            renderOrders(orders);

            if (newOrders.length > 0) {
                // Markeer als geprint voordat we printen (voorkomt dubbel printen bij snelle herlaad)
                markPrinted(newOrders.map(o => o.id));

                // Render alleen nieuwe orders voor printen
                renderOrders(newOrders);
                window.print();

                // Na printen alle bestellingen weer tonen
                renderOrders(orders);
            }

            const now = new Date().toLocaleTimeString('nl-NL');
            document.getElementById('status').textContent =
                `Laatste check: ${now} · ${orders.length} bestelling(en) vandaag · vernieuwd elke 20s`;

        } catch (e) {
            document.getElementById('status').textContent = '⚠️ Verbindingsfout — opnieuw proberen...';
        }
    }

    poll();
    setInterval(poll, POLL_INTERVAL);
</script>
</body>
</html>
