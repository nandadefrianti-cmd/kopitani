<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class OrderController extends Controller
{

    public function checkout(Product $product)
    {
        abort_unless(Auth::user()->role === 'kedai', 403);

        abort_unless(
            $product->status === 'approved' && $product->stock > 0,
            404
        );

        return view('orders.checkout', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        abort_unless(Auth::user()->role === 'kedai', 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'delivery_address' => ['required', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = DB::transaction(function () use ($validated, $product) {
            $product = Product::whereKey($product->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($product->status !== 'approved') {
                throw ValidationException::withMessages([
                    'quantity' => 'Produk belum disetujui admin.',
                ]);
            }

            if ($validated['quantity'] > $product->stock) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok produk tidak mencukupi.',
                ]);
            }

            if ($validated['quantity'] < $product->wholesale_min_qty) {
                throw ValidationException::withMessages([
                    'quantity' => 'Jumlah minimal pembelian adalah '
                        . $product->wholesale_min_qty . '.',
                ]);
            }

            $unitPrice = $product->wholesale_price;
            $total = $unitPrice * $validated['quantity'];

            $order = Order::create([
                'order_code' => 'KPT-' . strtoupper(Str::random(10)),
                'buyer_id' => Auth::id(),
                'farmer_id' => $product->farmer_id,
                'total_amount' => $total,
                'status' => 'menunggu_pembayaran',
                'delivery_address' => $validated['delivery_address'],
                'notes' => $validated['notes'] ?? null,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'unit_price' => $unitPrice,
                'subtotal' => $total,
            ]);

            $product->decrement('stock', $validated['quantity']);

            return $order;
        });

        return redirect()
            ->route('orders.index')
            ->with('success', 'Pesanan berhasil dibuat dengan kode '
                . $order->order_code . '.');
    }

    public function index()
    {
        abort_unless(
            in_array(Auth::user()->role, ['kedai', 'petani']),
            403
        );

        $query = Order::with(['items.product', 'buyer', 'farmer'])
            ->latest();

        if (Auth::user()->role === 'kedai') {
            $query->where('buyer_id', Auth::id());
        } else {
            $query->where('farmer_id', Auth::id());
        }

        $orders = $query->get();

        return view('orders.index', compact('orders'));
    }
    

public function updateStatus(Request $request, Order $order)
{
    $user = Auth::user();

    // Pastikan pengguna adalah pemilik pesanan terkait.
    abort_unless(
        ($user->role === 'petani' && $order->farmer_id == $user->id) ||
        ($user->role === 'kedai' && $order->buyer_id == $user->id),
        403
    );

    $validated = $request->validate([
        'status' => ['required', 'in:dibayar,dikirim,selesai'],
    ]);

    $currentStatus = $order->status;
    $newStatus = $validated['status'];
    $allowed = false;

    // Kedai mengonfirmasi pembayaran.
    if (
        $user->role === 'kedai' &&
        $currentStatus === 'menunggu_pembayaran' &&
        $newStatus === 'dibayar'
    ) {
        $allowed = true;
    }

    // Petani mengirim pesanan yang sudah dibayar.
    if (
        $user->role === 'petani' &&
        $currentStatus === 'dibayar' &&
        $newStatus === 'dikirim'
    ) {
        $allowed = true;
    }

    // Kedai mengonfirmasi pesanan sudah selesai.
    if (
        $user->role === 'kedai' &&
        $currentStatus === 'dikirim' &&
        $newStatus === 'selesai'
    ) {
        $allowed = true;
    }

    if (!$allowed) {
        return back()->with('error', 'Perubahan status tidak diizinkan.');
    }

    $order->update([
        'status' => $newStatus,
    ]);

    return back()->with('success', 'Status pesanan berhasil diperbarui.');
}


}
