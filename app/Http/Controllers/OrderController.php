<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\User;
use App\Notifications\KopiTaniNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    // Menampilkan halaman checkout
    public function checkout(Product $product)
    {
        abort_unless(Auth::user()->role === 'kedai', 403);

        abort_unless(
            $product->status === 'approved' && $product->stock > 0,
            404
        );

        return view('orders.checkout', compact('product'));
    }

    // Membuat pesanan baru
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
                'payment_status' => 'menunggu_pembayaran',
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

            // Stok tetap dikurangi ketika checkout berhasil.
            $product->decrement('stock', $validated['quantity']);

            // Mencatat aktivitas pesanan.
            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'activity' => 'Pesanan Dibuat',
                'description' => 'Kedai membuat pesanan dan menunggu pembayaran.',
            ]);

            return $order;
        });

        // Memberi tahu Petani bahwa ada pesanan baru.
        $order->farmer->notify(new KopiTaniNotification(
            'Pesanan Baru',
            'Kamu menerima pesanan baru dengan kode ' . $order->order_code . '.',
            url('/orders'),
            'order'
        ));

        return redirect()
            ->route('orders.index')
            ->with('success', 'Pesanan berhasil dibuat dengan kode '
                . $order->order_code . '.');
    }

    // Menampilkan pesanan berdasarkan role pengguna.
    public function index()
    {
        abort_unless(
            in_array(Auth::user()->role, ['kedai', 'petani']),
            403
        );

        $query = Order::with([
            'items.product',
            'buyer',
            'farmer',
            'histories.user',
        ])->latest();

        if (Auth::user()->role === 'kedai') {
            $query->where('buyer_id', Auth::id());
        } else {
            $query->where('farmer_id', Auth::id());
        }

        $orders = $query->get();

        return view('orders.index', compact('orders'));
    }

    // Kedai mengunggah bukti pembayaran.
    public function uploadPaymentProof(Request $request, Order $order)
    {
        abort_unless(
            Auth::user()->role === 'kedai' &&
            $order->buyer_id == Auth::id(),
            403
        );

        abort_unless($order->status === 'menunggu_pembayaran', 403);

        abort_unless(
            in_array($order->payment_status, [
                'menunggu_pembayaran',
                'ditolak',
            ]),
            403
        );

        $validated = $request->validate([
            'payment_proof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
        ]);

        // Bukti pembayaran disimpan di storage private.
        $path = $request->file('payment_proof')
            ->store('payment-proofs', 'local');

        // Hapus bukti sebelumnya jika Kedai mengunggah ulang.
        if ($order->payment_proof_path) {
            Storage::disk('local')->delete($order->payment_proof_path);
        }

        $order->update([
            'payment_proof_path' => $path,
            'payment_status' => 'menunggu_konfirmasi',
            'payment_rejection_reason' => null,
        ]);

        OrderHistory::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'activity' => 'Bukti Pembayaran Diunggah',
            'description' => 'Kedai mengunggah bukti transfer dan menunggu konfirmasi Petani.',
        ]);

        $order->farmer->notify(new KopiTaniNotification(
            'Bukti Pembayaran Baru',
            'Kedai telah mengunggah bukti pembayaran untuk pesanan '
                . $order->order_code . '.',
            url('/orders'),
            'payment'
        ));

        return back()->with(
            'success',
            'Bukti pembayaran berhasil dikirim. Menunggu konfirmasi Petani.'
        );
    }

    // Petani menyetujui pembayaran.
    public function confirmPayment(Order $order)
    {
        abort_unless(
            Auth::user()->role === 'petani' &&
            $order->farmer_id == Auth::id(),
            403
        );

        abort_unless(
            $order->status === 'menunggu_pembayaran' &&
            $order->payment_status === 'menunggu_konfirmasi' &&
            $order->payment_proof_path,
            403
        );

        // Petani wajib memastikan uang benar-benar masuk ke rekening.
        $order->update([
            'payment_status' => 'dibayar',
            'status' => 'dibayar',
            'payment_rejection_reason' => null,
            'paid_at' => now(),
        ]);

        OrderHistory::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'activity' => 'Pembayaran Dikonfirmasi',
            'description' => 'Petani telah mengonfirmasi pembayaran.',
        ]);

        $order->buyer->notify(new KopiTaniNotification(
            'Pembayaran Dikonfirmasi',
            'Pembayaran pesanan ' . $order->order_code
                . ' telah dikonfirmasi Petani.',
            url('/orders'),
            'success'
        ));

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    // Petani menolak bukti pembayaran.
    public function rejectPayment(Request $request, Order $order)
    {
        abort_unless(
            Auth::user()->role === 'petani' &&
            $order->farmer_id == Auth::id(),
            403
        );

        abort_unless(
            $order->status === 'menunggu_pembayaran' &&
            $order->payment_status === 'menunggu_konfirmasi',
            403
        );

        $validated = $request->validate([
            'payment_rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $order->update([
            'payment_status' => 'ditolak',
            'payment_rejection_reason' => $validated['payment_rejection_reason'],
        ]);

        OrderHistory::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'activity' => 'Pembayaran Ditolak',
            'description' => 'Alasan: ' . $validated['payment_rejection_reason'],
        ]);

        $order->buyer->notify(new KopiTaniNotification(
            'Pembayaran Ditolak',
            'Bukti pembayaran pesanan ' . $order->order_code
                . ' ditolak. Alasan: '
                . $validated['payment_rejection_reason'],
            url('/orders'),
            'danger'
        ));

        return back()->with(
            'success',
            'Bukti pembayaran ditolak. Kedai dapat mengunggah ulang bukti transfer.'
        );
    }

    // Petani memperbarui status pengiriman.
    public function shipOrder(Order $order)
    {
        abort_unless(
            Auth::user()->role === 'petani' &&
            $order->farmer_id == Auth::id(),
            403
        );

        abort_unless(
            $order->status === 'dibayar' &&
            $order->payment_status === 'dibayar',
            403
        );

        $order->update([
            'status' => 'dikirim',
        ]);

        OrderHistory::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'activity' => 'Pesanan Dikirim',
            'description' => 'Petani telah mengirim pesanan kepada Kedai.',
        ]);

        $order->buyer->notify(new KopiTaniNotification(
            'Pesanan Dikirim',
            'Pesanan ' . $order->order_code . ' telah dikirim Petani.',
            url('/orders'),
            'order'
        ));

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi dikirim.');
    }

    // Kedai mengonfirmasi barang telah diterima.
    public function confirmReceived(Order $order)
    {
        abort_unless(
            Auth::user()->role === 'kedai' &&
            $order->buyer_id == Auth::id(),
            403
        );

        abort_unless($order->status === 'dikirim', 403);

        $order->update([
            'status' => 'selesai',
        ]);

        OrderHistory::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'activity' => 'Pesanan Selesai',
            'description' => 'Kedai telah mengonfirmasi barang diterima.',
        ]);

        $order->farmer->notify(new KopiTaniNotification(
            'Pesanan Selesai',
            'Kedai telah mengonfirmasi penerimaan pesanan '
                . $order->order_code . '.',
            url('/orders'),
            'success'
        ));

        return back()->with('success', 'Pesanan berhasil diselesaikan.');
    }

    // Menampilkan bukti pembayaran secara aman kepada pihak terkait.
    public function showPaymentProof(Order $order)
    {
        $user = Auth::user();

        abort_unless(
            ($user->role === 'petani' && $order->farmer_id == $user->id) ||
            ($user->role === 'kedai' && $order->buyer_id == $user->id),
            403
        );

        abort_unless($order->payment_proof_path, 404);

        abort_unless(
            Storage::disk('local')->exists($order->payment_proof_path),
            404
        );

        return Storage::disk('local')->download(
            $order->payment_proof_path
        );
    }
}
