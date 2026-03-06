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

        .status-buttons { margin-top: 12px; display: flex; gap: 6px; flex-wrap: wrap; }
        .btn-status { padding: 6px 12px; border: none; border-radius: 6px; font-size: 0.8rem; font-weight: bold; cursor: pointer; transition: opacity 0.15s; }
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

        .printed-badge     { display:inline-block; background:#065f46; color:#6ee7b7; font-size:0.65rem; padding:1px 6px; border-radius:4px; font-weight:bold; }
        .not-printed-badge { display:inline-block; background:#7f1d1d; color:#fca5a5; font-size:0.65rem; padding:1px 6px; border-radius:4px; font-weight:bold; }

        /* Print wachtrij indicator */
        #print-queue-bar {
            display: none;
            background: #1d4ed8;
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        #print-queue-bar.active { display: block; }

        /* ===== PRINT STIJLEN ===== */
        /* Scherm: verberg het print-area div */
        #print-area { display: none; }

        @media print {
            /* Verberg alles behalve print-area */
            #screen-ui, .orders-grid, #print-queue-bar { display: none !important; }
            body { background: #fff; color: #000; padding: 0; margin: 0; }

            /* Toon print-area */
            #print-area {
                display: block !important;
                font-family: monospace;
                font-size: 12pt;
                width: 80mm; /* Thermisch papier breedte */
                margin: 0;
                padding: 4mm;
            }
            .bon-title   { font-size: 14pt; font-weight: bold; text-align: center; }
            .bon-center  { text-align: center; }
            .bon-line    { border-top: 1px dashed #000; margin: 4px 0; }
            .bon-type    { font-size: 13pt; font-weight: bold; text-align: center; padding: 2px 0; }
            .bon-item    { font-size: 12pt; }
            .bon-qty     { font-weight: bold; }
            .bon-notes   { font-weight: bold; border: 1px solid #000; padding: 3px; margin-top: 4px; }
        }
    </style>
</head>
<body>

<div id="screen-ui">
    <h1>Keuken Display</h1>
    <div id="print-queue-bar"></div>
    <div id="status">Laden...</div>
</div>

{{-- Verborgen print-gebied: hier komt één bon tegelijk --}}
<div id="print-area"></div>

<div class="orders-grid" id="orders-container"></div>

<script>
    const POLL_INTERVAL = 15000;
    const CSRF_TOKEN    = document.querySelector('meta[name="csrf-token"]').content;

    // ===== PRINT WACHTRIJ =====
    const printQueue = [];
    let isPrinting   = false;

    function enqueuePrint(order) {
        printQueue.push(order);
        updateQueueBar();
        if (!isPrinting) processPrintQueue();
    }

    async function processPrintQueue() {
        if (printQueue.length === 0) {
            isPrinting = false;
            updateQueueBar();
            return;
        }

        isPrinting = true;
        const order = printQueue.shift();
        updateQueueBar();

        // Vul het print-gebied met deze bon
        document.getElementById('print-area').innerHTML = buildBonHtml(order);

        // Print (zonder dialoog bij Chrome met --kiosk-printing)
        window.print();

        // Markeer als geprint in de database
        try {
            await fetch(`/keuken/geprint/${order.id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
            });
        } catch (e) { /* niet fataal */ }

        // Korte pauze zodat de printer de bon kan verwerken
        await new Promise(r => setTimeout(r, 800));

        processPrintQueue();
    }

    function updateQueueBar() {
        const bar = document.getElementById('print-queue-bar');
        const total = printQueue.length + (isPrinting ? 1 : 0);
        if (total > 0) {
            bar.textContent = `Printing... ${total} bon(nen) in wachtrij`;
            bar.classList.add('active');
        } else {
            bar.classList.remove('active');
        }
    }

    function buildBonHtml(order) {
        const type = order.type === 'delivery' ? 'BEZORGING' : 'AFHALEN';
        const items = order.items.map(i =>
            `<div class="bon-item"><span class="bon-qty">${i.quantity}x</span> ${i.name}</div>`
        ).join('');
        const adres = order.type === 'delivery' && order.delivery_address
            ? `<div>${order.delivery_address}</div>` : '';
        const notes = order.notes
            ? `<div class="bon-notes">! LET OP: ${order.notes}</div>` : '';

        return `
            <div class="bon-title">{{ config('app.name', 'Restaurant') }}</div>
            <div class="bon-center">BON #${order.order_number}</div>
            <div class="bon-center">${order.created_at}</div>
            <div class="bon-line"></div>
            <div class="bon-type">&mdash; ${type} &mdash;</div>
            <div class="bon-line"></div>
            <div><strong>${order.customer_name}</strong></div>
            <div>Tel: ${order.customer_phone}</div>
            ${adres}
            <div class="bon-line"></div>
            ${items}
            ${notes}
            <div class="bon-line"></div>
        `;
    }

    // ===== STATUS KNOPPEN =====
    const statusLabels = {
        pending:   'Wacht',
        confirmed: 'Bevestigd',
        preparing: 'In bereiding',
        ready:     'Klaar',
        delivered: 'Bezorgd/Opgehaald',
        cancelled: 'Geannuleerd',
    };

    async function updateStatus(orderId, newStatus) {
        try {
            await fetch(`/keuken/status/${orderId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify({ status: newStatus }),
            });
            poll();
        } catch (e) {
            alert('Fout bij statuswijziging.');
        }
    }

    // ===== RENDEREN =====
    function renderOrders(orders) {
        const container = document.getElementById('orders-container');
        container.innerHTML = '';

        const sorted = [...orders].sort((a, b) => {
            const ord = ['pending','confirmed','preparing','ready','delivered','cancelled'];
            return ord.indexOf(a.status) - ord.indexOf(b.status);
        });

        sorted.forEach(order => {
            const div = document.createElement('div');
            div.className = `order status-${order.status}`;

            const printBadge = order.printed_at
                ? `<span class="printed-badge">GEPRINT ${order.printed_at}</span>`
                : `<span class="not-printed-badge">IN WACHTRIJ</span>`;

            const statusBtns = [];
            if (order.status === 'pending')   statusBtns.push(`<button class="btn-status btn-confirm"   onclick="updateStatus(${order.id},'confirmed')">Bevestig</button>`);
            if (order.status === 'confirmed') statusBtns.push(`<button class="btn-status btn-preparing" onclick="updateStatus(${order.id},'preparing')">Start bereiding</button>`);
            if (order.status === 'preparing') statusBtns.push(`<button class="btn-status btn-ready"     onclick="updateStatus(${order.id},'ready')">Klaar</button>`);
            if (order.status === 'ready')     statusBtns.push(`<button class="btn-status btn-delivered" onclick="updateStatus(${order.id},'delivered')">Bezorgd/Opgehaald</button>`);
            if (!['delivered','cancelled'].includes(order.status)) {
                statusBtns.push(`<button class="btn-status btn-cancel" onclick="updateStatus(${order.id},'cancelled')">Annuleer</button>`);
            }

            div.innerHTML = `
                <div class="order-header">
                    <strong>#${order.order_number}</strong>
                    <span class="badge ${order.type === 'delivery' ? 'bezorging' : 'afhalen'}">
                        ${order.type === 'delivery' ? 'BEZORGING' : 'AFHALEN'}
                    </span>
                    ${printBadge}
                    <span class="current-status ${order.status}" style="margin-left:auto">${statusLabels[order.status] || order.status}</span>
                    <small style="color:#aaa">${order.created_at}</small>
                </div>
                <div class="order-meta">
                    <strong>${order.customer_name}</strong> &nbsp; ${order.customer_phone}
                    ${order.type === 'delivery' ? '<br>' + (order.delivery_address || '') : ''}
                </div>
                <div class="order-items">
                    ${order.items.map(i => `<div class="item"><span>${i.quantity}x</span> ${i.name}</div>`).join('')}
                </div>
                ${order.notes ? `<div class="notes">! ${order.notes}</div>` : ''}
                <div class="status-buttons">${statusBtns.join('')}</div>
            `;
            container.appendChild(div);
        });
    }

    // ===== POLLING =====
    // Houd bij welke IDs al in de wachtrij staan (in deze browsersessie)
    const queuedIds = new Set();

    async function poll() {
        try {
            const res    = await fetch('{{ route("kitchen.orders") }}');
            const orders = await res.json();

            // Voeg nieuwe (ongeprinte) bestellingen toe aan de wachtrij
            orders
                .filter(o => !o.printed_at && o.status !== 'cancelled' && !queuedIds.has(o.id))
                .forEach(o => {
                    queuedIds.add(o.id);
                    enqueuePrint(o);
                });

            renderOrders(orders);

            const now      = new Date().toLocaleTimeString('nl-NL');
            const active   = orders.filter(o => !['delivered','cancelled'].includes(o.status)).length;
            const inQueue  = printQueue.length + (isPrinting ? 1 : 0);
            const queueTxt = inQueue > 0 ? ` · ${inQueue} in print-wachtrij` : ' · Alles geprint';

            document.getElementById('status').textContent =
                `Laatste check: ${now} · ${active} actieve bestelling(en)${queueTxt}`;

        } catch (e) {
            document.getElementById('status').textContent = 'Verbindingsfout — opnieuw proberen...';
        }
    }

    poll();
    setInterval(poll, POLL_INTERVAL);
</script>
</body>
</html>
