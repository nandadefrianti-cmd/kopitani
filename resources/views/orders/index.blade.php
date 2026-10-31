
@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8">
    <h1 class="mb-6 text-2xl font-bold">Daftar Pesanan</h1>

    {{-- Notifikasi berhasil --}}
    @if (session('success'))
        <div class="mb-5 rounded-lg bg-green-100 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi gagal --}}
    @if (session('error'))
        <div class="mb-5 rounded-lg bg-red-100 p-4 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Pesan validasi --}}
    @if ($errors->any())
        <div class="mb-5 rounded-lg bg-red-100 p-4 text-red-800">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($orders->isEmpty())
        <div class="rounded-xl border border-gray-200 p-8 text-center">
            <h2 class="text-lg font-semibold">Belum ada pesanan</h2>
            <p class="mt-2 text-gray-600">
                Pesanan yang kamu buat atau terima akan muncul di sini.
            </p>
        </div>
    @else
        <div class="space-y-5">
            @foreach ($orders as $order)
                <div class="rounded-xl border border-gray-200 p-5 shadow-sm">

                    {{-- Header pesanan --}}
                    <div class="flex flex-col justify-between gap-3 sm:flex-row">
                        <div>
                            <h2 class="text-lg font-bold">
                                {{ $order->order_code }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $order->created_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') }}
                            </p>
                        </div>

                        <div>
                            <span class="inline-block rounded-full px-3 py-1 text-sm font-medium
                                @if ($order->status === 'menunggu_pembayaran')
                                    bg-yellow-100 text-yellow-800
                                @elseif ($order->status === 'dibayar')
                                    bg-blue-100 text-blue-800
                                @elseif ($order->status === 'dikirim')
                                    bg-purple-100 text-purple-800
                                @else
                                    bg-green-100 text-green-800
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>
                    </div>

                    {{-- Informasi Petani dan Kedai --}}
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-500">
                                {{ auth()->user()->role === 'kedai' ? 'Petani' : 'Pemilik Kedai' }}
                            </p>
                            <p class="font-medium">
                                {{ auth()->user()->role === 'kedai' ? $order->farmer->name : $order->buyer->name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Total Pesanan</p>
                            <p class="font-semibold">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Daftar produk --}}
                    <div class="mt-4 border-t pt-4">
                        <h3 class="mb-3 font-semibold">Produk</h3>

                        @foreach ($order->items as $item)
                            <div class="mb-3 flex flex-col justify-between gap-1 sm:flex-row">
                                <div>
                                    <p class="font-medium">
                                        {{ $item->product->name ?? 'Produk sudah tidak tersedia' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $item->quantity }} ×
                                        Rp{{ number_format($item->unit_price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <p class="font-medium">
                                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Alamat pengiriman --}}
                    <div class="mt-4 border-t pt-4">
                        <p class="text-sm text-gray-500">Alamat Pengiriman</p>
                        <p>{{ $order->delivery_address }}</p>

                        @if ($order->notes)
                            <p class="mt-2 text-sm">
                                <span class="font-medium">Catatan:</span>
                                {{ $order->notes }}
                            </p>
                        @endif
                    </div>

                    {{-- Informasi pembayaran --}}
                    <div class="mt-4 rounded-lg bg-gray-50 p-4">
                        <h3 class="mb-3 font-semibold">Informasi Pembayaran</h3>

                        <p class="text-sm text-gray-500">Status Pembayaran</p>

                        <span class="mt-1 inline-block rounded-full px-3 py-1 text-sm font-medium
                            @if ($order->payment_status === 'dibayar')
                                bg-green-100 text-green-800
                            @elseif ($order->payment_status === 'menunggu_konfirmasi')
                                bg-blue-100 text-blue-800
                            @elseif ($order->payment_status === 'ditolak')
                                bg-red-100 text-red-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucwords(str_replace('_', ' ', $order->payment_status)) }}
                        </span>

                        @if ($order->paid_at)
                            <p class="mt-2 text-sm text-gray-600">
                                Dibayar pada:
                                {{ $order->paid_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') }}
                            </p>
                        @endif

                        {{-- Alasan pembayaran ditolak --}}
                        @if ($order->payment_status === 'ditolak' && $order->payment_rejection_reason)
                            <div class="mt-3 rounded-lg bg-red-50 p-3 text-sm text-red-800">
                                <strong>Alasan pembayaran ditolak:</strong>
                                <p>{{ $order->payment_rejection_reason }}</p>
                            </div>
                        @endif

                        {{-- Bukti transfer yang sudah diunggah --}}
                        @if ($order->payment_proof_path)
                            <div class="mt-3">
                                <a href="{{ route('orders.showPaymentProof', $order) }}"
                                   class="inline-block text-sm font-medium text-blue-700 underline">
                                    Lihat / Unduh Bukti Transfer
                                </a>
                            </div>
                        @endif

                        {{-- Form upload bukti transfer oleh Kedai --}}
                        @if (
                            auth()->user()->role === 'kedai' &&
                            $order->status === 'menunggu_pembayaran' &&
                            in_array($order->payment_status, ['menunggu_pembayaran', 'ditolak'])
                        )
                            <form action="{{ route('orders.paymentProof', $order) }}"
                                  method="POST"
                                  enctype="multipart/form-data"
                                  class="mt-4 border-t border-gray-200 pt-4">
                                @csrf

                                <label class="mb-2 block text-sm font-medium">
                                    {{ $order->payment_status === 'ditolak' ? 'Unggah Ulang Bukti Transfer' : 'Unggah Bukti Transfer' }}
                                </label>

                                <input type="file"
                                       name="payment_proof"
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       required
                                       class="block w-full rounded-lg border border-gray-300 bg-white p-2 text-sm">

                                <p class="mt-1 text-xs text-gray-500">
                                    Format JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
                                </p>

                                <button type="submit"
                                        class="mt-3 rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800">
                                    Kirim Bukti Transfer
                                </button>
                            </form>
                        @endif

                        {{-- Konfirmasi atau penolakan pembayaran oleh Petani --}}
                        @if (
                            auth()->user()->role === 'petani' &&
                            $order->payment_status === 'menunggu_konfirmasi'
                        )
                            <div class="mt-4 border-t border-gray-200 pt-4">
                                <p class="mb-3 text-sm text-gray-600">
                                    Periksa bukti transfer dan pastikan dana benar-benar masuk ke rekening sebelum menyetujui pembayaran.
                                </p>

                                <form action="{{ route('orders.confirmPayment', $order) }}"
                                      method="POST"
                                      onsubmit="return confirm('Pastikan uang sudah benar-benar masuk ke rekening. Konfirmasi pembayaran?')">
                                    @csrf

                                    <button type="submit"
                                            class="rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800">
                                        Konfirmasi Pembayaran
                                    </button>
                                </form>

                                <form action="{{ route('orders.rejectPayment', $order) }}"
                                      method="POST"
                                      class="mt-3">
                                    @csrf

                                    <label class="mb-2 block text-sm font-medium">
                                        Alasan Penolakan
                                    </label>

                                    <textarea name="payment_rejection_reason"
                                              rows="2"
                                              required
                                              maxlength="1000"
                                              placeholder="Tuliskan alasan penolakan bukti transfer..."
                                              class="w-full rounded-lg border border-gray-300 p-3 text-sm"></textarea>

                                    <button type="submit"
                                            class="mt-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                                        Tolak Pembayaran
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    {{-- Aksi pengiriman oleh Petani --}}
                    @if (
                        auth()->user()->role === 'petani' &&
                        $order->status === 'dibayar' &&
                        $order->payment_status === 'dibayar'
                    )
                        <div class="mt-4">
                            <form action="{{ route('orders.ship', $order) }}"
                                  method="POST"
                                  onsubmit="return confirm('Tandai pesanan ini sudah dikirim?')">
                                @csrf

                                <button type="submit"
                                        class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">
                                    Tandai Sudah Dikirim
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Konfirmasi penerimaan oleh Kedai --}}
                    @if (
                        auth()->user()->role === 'kedai' &&
                        $order->status === 'dikirim'
                    )
                        <div class="mt-4">
                            <form action="{{ route('orders.confirmReceived', $order) }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah barang sudah diterima?')">
                                @csrf

                                <button type="submit"
                                        class="rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800">
                                    Konfirmasi Barang Diterima
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Riwayat aktivitas pesanan --}}
                    <div class="mt-5 border-t pt-4">
                        <h3 class="mb-3 font-semibold">Riwayat Aktivitas</h3>

                        @forelse ($order->histories as $history)
                            <div class="mb-3 border-l-2 border-gray-300 pl-4">
                                <p class="font-medium text-sm">
                                    {{ $history->activity }}
                                </p>

                                @if ($history->description)
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $history->description }}
                                    </p>
                                @endif

                                <p class="mt-1 text-xs text-gray-400">
                                    {{ $history->created_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">
                                Belum ada riwayat aktivitas.
                            </p>
                        @endforelse
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
