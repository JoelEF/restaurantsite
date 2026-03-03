<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuken Display</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { box-sizing: border-box; }
        body { font-family: monospace; background: #111; color: #fff; margin: 0; padding: 16px; }
        h1 { font-size: 1.2rem; margin: 0 0 4px; color: #f97316; }
        #status { font-size: 0.75rem; color: #aaa; margin-bottom: 16px; }

        .orders-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; }

        .order { background: #1e1e1e; border: 2px solid #f97316; border-radius: 8px; padding: 16px; position: relative; }
        .order.status-delivered { border-color: #374151; opacity: 0.5; }
        .order.status-cancelled { border-color: #7f1d1d; opacity: 0.4; }
        .order.status-ready     { border-color: #15803d; }
        .order.status-preparing { border-color: #1d4ed8; }
        .order.status-confirmed { border-color: #f59e0b; }

        .order-header { font-size: 1.1rem; font-weight: bold; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .order-meta { font-size: 0.85rem; color: #ccc; margin-bottom: 8px; }
        .order-items { border-top: 1px dashed #555; padding-top: 8px; }
        .item { font-size: 1rem; margin-bottom: 4px; }
        .item span { font-size: 1.2rem; font-weight: bold; color: #f97316; }
        .badge { display:inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }
        .bezorging { background: #1d4ed8; }
        .afhalen   { background: #15803d; }
        .notes { margin-top: 8px; border-top: 1px dashed #555; padding-top: 8px; font-size: 0.85rem; color: #fbbf24; }

        /* Status knoppen */
        .status-buttons { margin-top: 12px; display: flex; gap: 6px; flex-wrap: wrap; }
        .btn-status {
            padding: 6px 12px; border: none; border-radius: 6px; font-size: 0.8rem;
            font-weight: bold; cursor: pointer; transition: opacity 0.15s;
        }
        .btn-status:hover { opacity: 0.8; }
        .btn-confirm   { background: #f59e0b; color: #000; }
        .btn-preparing { background: #1d4ed8; color: #fff; }
        .btn-ready     { background: #15803d; color: #fff; }
        .btn-delivered { background: #374151; color: #ccc; }
        .btn-cancel    { background: #7f1d1d; color: #fca5a5; }

        .current-status { font-size: 0.75rem; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em; }
        .current-status.pending   { color: #9ca3af; }
        .current-status.confirmed { color: #f59e0b; }
        .current-status.preparing { color: #60a5fa; }
        .current-status.ready     { color: #4ade80; }
        .current-status.delivered { color: #6b7280; }
        .current-status.cancelled { color: #f87171; }

        @media print {
            body { background: #fff; color: #000; padding: 0; }
            #screen-ui { display: none; }
            .status-buttons { display: none; }
            .orders-grid { display: block; }
            .order { border: 2px dashed #000; color: #000; background: #fff; page-break-after: always;
                     break-after: page; margin-bottom: 0; opacity: 1 !important; }
            .order-meta { color: #333; }
            .badge.bezorging { background: #93c5fd; color: #000; }
            .badge.afhalen   { background: #86efac; color: #000; }
            .notes { color: #78350f; }
            .item span { color: #000; }
            .current-status { display: none; }
        }
    </style>
</head>
<body>

<div id="screen-ui">
    <h1>Keuken Display</h1>
    <div id="status">Laden...</div>
</div>

<div class="orders-grid" id="orders-container"></div>

<script>
    const POLL_INTERVAL = 20000;
    const STORAGE_KEY   = 'printed_order_ids';
    const CSRF_TOKEN    = document.querySelector('meta[name="csrf-token"]').content;

    const statusLabels = {
        pending:   '⏳ Wacht op bevestiging',
        confirmed: '✅ Bevestigd',
        preparing: '👨‍🍳 In bereiding',
        ready:     '🎉 Klaar',
        delivered: '🏠 Bezorgd/Opgehaald',
        cancelled: '❌ Geannuleerd',
    };

    function getPrinted() {
        return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    }

    function markPrinted(ids) {
        const existing = getPrinted();
        localStorage.setItem(STORAGE_KEY, JSON.stringify([...new Set([...existing, ...ids])]));
    }

    async function updateStatus(orderId, newStatus) {
        try {
            const res = await fetch(`/keuken/status/${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                body: JSON.stringify({ status: newStatus }),
            });
            if (res.ok) {
                poll(); // Direct verversen
            }
        } catch (e) {
            alert('Fout bij statuswijziging. Probeer opnieuw.');
        }
    }

    function renderOrders(orders) {
        const container = document.getElementById('orders-container');
        container.innerHTML = '';

        // Sorteer: actieve bestellingen bovenaan, afgeleverd onderaan
        const sorted = [...orders].sort((a, b) => {
            const order = ['pending','confirmed','preparing','ready','delivered','cancelled'];
            return order.indexOf(a.status) - order.indexOf(b.status);
        });

        sorted.forEach(order => {
            const div = document.createElement('div');
            div.className = `order status-${order.status}`;

            const statusBtns = [];
            if (order.status === 'pending')   statusBtns.push(`<button class="btn-status btn-confirm"   onclick="updateStatus(${order.id},'confirmed')">✅ Bevestig</button>`);
            if (order.status === 'confirmed') statusBtns.push(`<button class="btn-status btn-preparing" onclick="updateStatus(${order.id},'preparing')">👨‍🍳 Start bereiding</button>`);
            if (order.status === 'preparing') statusBtns.push(`<button class="btn-status btn-ready"     onclick="updateStatus(${order.id},'ready')">🎉 Klaar</button>`);
            if (order.status === 'ready')     statusBtns.push(`<button class="btn-status btn-delivered" onclick="updateStatus(${order.id},'delivered')">🏠 Bezorgd/Opgehaald</button>`);
            if (!['delivered','cancelled'].includes(order.status)) {
                statusBtns.push(`<button class="btn-status btn-cancel" onclick="updateStatus(${order.id},'cancelled')">❌ Annuleer</button>`);
            }

            div.innerHTML = `
                <div class="order-header">
                    <strong>#${order.order_number}</strong>
                    <span class="badge ${order.type === 'delivery' ? 'bezorging' : 'afhalen'}">
                        ${order.type === 'delivery' ? '🚚 BEZORGING' : '🏃 AFHALEN'}
                    </span>
                    <span class="current-status ${order.status}">${statusLabels[order.status] || order.status}</span>
                    <small style="color:#aaa;margin-left:auto">${order.created_at}</small>
                </div>
                <div class="order-meta">
                    <strong>${order.customer_name}</strong> &nbsp;📞 ${order.customer_phone}
                    ${order.type === 'delivery' ? '<br>📍 ' + (order.delivery_address || '') : ''}
                </div>
                <div class="order-items">
                    ${order.items.map(i => `<div class="item"><span>${i.quantity}x</span> ${i.name}</div>`).join('')}
                </div>
                ${order.notes ? `<div class="notes">⚠️ ${order.notes}</div>` : ''}
                <div class="status-buttons">${statusBtns.join('')}</div>
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

            // Altijd alle bestellingen tonen
            renderOrders(orders);

            if (newOrders.length > 0) {
                markPrinted(newOrders.map(o => o.id));

                // Print alleen de nieuwe bestellingen
                const container = document.getElementById('orders-container');
                const all = container.innerHTML;
                renderOrders(newOrders);
                window.print();
                renderOrders(orders);
            }

            const now = new Date().toLocaleTimeString('nl-NL');
            const active = orders.filter(o => !['delivered','cancelled'].includes(o.status)).length;
            document.getElementById('status').textContent =
                `Laatste check: ${now} · ${active} actieve bestelling(en) · vernieuwd elke 20s`;

        } catch (e) {
            document.getElementById('status').textContent = '⚠️ Verbindingsfout — opnieuw proberen...';
        }
    }

    poll();
    setInterval(poll, POLL_INTERVAL);
</script>
</body>
</html>
