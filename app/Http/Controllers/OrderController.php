<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\BusinessHour;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $isOpen = BusinessHour::isOpenNow();
        $todayHours = BusinessHour::todayHours();
        return view('order', compact('isOpen', 'todayHours'));
    }

    public function store(Request $request)
    {
        if (!BusinessHour::isOpenNow()) {
            return back()->withErrors(['open' => 'Het restaurant is momenteel gesloten. Bestellingen kunnen niet worden geplaatst.']);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'type' => 'required|in:delivery,pickup',
            'delivery_address' => 'required_if:type,delivery|nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $subtotal = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $menuItem = MenuItem::findOrFail($item['id']);
            $itemSubtotal = $menuItem->price * $item['quantity'];
            $subtotal += $itemSubtotal;
            $orderItems[] = [
                'menu_item_id' => $menuItem->id,
                'name' => $menuItem->name,
                'price' => $menuItem->price,
                'quantity' => $item['quantity'],
                'subtotal' => $itemSubtotal,
                'notes' => $item['notes'] ?? null,
            ];
        }

        $deliveryFee = $validated['type'] === 'delivery' ? 2.50 : 0;
        $total = $subtotal + $deliveryFee;

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'type' => $validated['type'],
            'delivery_address' => $validated['delivery_address'] ?? null,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        session()->forget('cart');

        return redirect()->route('order.confirmation', $order)->with('success', 'Bestelling geplaatst!');
    }

    public function confirmation(Order $order)
    {
        $order->load('items');
        return view('order-confirmation', compact('order'));
    }

    public function kitchen()
    {
        return view('kitchen');
    }

    public function kitchenOrders()
    {
        $orders = Order::with('items')
            ->where('created_at', '>=', now()->subHours(12))
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($order) => [
                'id'           => $order->id,
                'order_number' => $order->order_number,
                'created_at'   => $order->created_at->format('H:i'),
                'type'         => $order->type,
                'status'       => $order->status,
                'customer_name'  => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'delivery_address' => $order->delivery_address,
                'notes'        => $order->notes,
                'printed_at'   => $order->printed_at?->format('H:i'),
                'items'        => $order->items->map(fn($i) => [
                    'name'     => $i->name,
                    'quantity' => $i->quantity,
                ]),
            ]);

        return response()->json($orders);
    }

    public function printBon(Order $order)
    {
        $order->load('items');
        $data = [
            'order_number'     => $order->order_number,
            'created_at'       => $order->created_at->format('d-m-Y H:i'),
            'type'             => $order->type,
            'customer_name'    => $order->customer_name,
            'customer_phone'   => $order->customer_phone,
            'delivery_address' => $order->delivery_address,
            'notes'            => $order->notes,
            'items'            => $order->items->map(fn($i) => [
                'name'     => $i->name,
                'quantity' => $i->quantity,
            ])->toArray(),
        ];
        return view('kitchen-bon', ['order' => $data]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['ok' => true, 'status' => $order->status]);
    }

    public function track(Request $request)
    {
        $order = null;
        if ($request->has('order_number')) {
            $order = Order::where('order_number', $request->order_number)->first();
        }
        return view('order-track', compact('order'));
    }
}
