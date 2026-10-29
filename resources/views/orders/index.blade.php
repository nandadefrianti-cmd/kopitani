
@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8">
    <h1 class="mb-6 text-2xl font-bold">Daftar Pesanan</h1>

    @if (session('success'))
        <div class="mb-5 rounded-lg bg-green-100 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 rounded-lg bg-red-100 p-4 text-red-800">
            {{ session('error') }}
        </div>
    @endif

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
                    <div class="flex flex-col justify-between gap-3 sm:flex-row">
                        <div>
                            <h2 class="text-lg font-bold">
                                {{ $order->order_code }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <div class="flex flex-col items-start gap-2">
                            <span class="rounded-full px-3 py-1 text-sm font-medium
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

                            @if (auth()->user()->role === 'kedai' && $order->status === 'menunggu_pembayaran')
                                <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="dibayar">
                                    <button type="submit"
                                        class="rounded-lg bg-green-700 px-3 py-2 text-sm font-medium text-white hover:bg-green-800">
                                        Konfirmasi Pembayaran
                                    </button>
                                </form>
                            @elseif (auth()->user()->role === 'petani' && $order->status === 'dibayar')
                                <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="dikirim">
                                    <button type="submit"
                                        class="rounded-lg bg-blue-700 px-3 py-2 text-sm font-medium text-white hover:bg-blue-800">
                                        Tandai Dikirim
                                    </button>
                                </form>
                            @elseif (auth()->user()->role === 'kedai' && $order->status === 'dikirim')
                                <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="selesai">
                                    <button type="submit"
                                        class="rounded-lg bg-green-700 px-3 py-2 text-sm font-medium text-white hover:bg-green-800">
                                        Pesanan Selesai
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

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

                    <div class="mt-4 border-t pt-4">
                        <h3 class="mb-3 font-semibold">Produk</h3>

                        @foreach ($order->items as $item)
                            <div class="mb-3 flex flex-col justify-between gap-1 sm:flex-row">
                                <div>
                                    <p class="font-medium">
                                        {{ $item->product->name }}
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
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection