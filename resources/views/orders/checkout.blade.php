
@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-8">
    <h1 class="mb-6 text-2xl font-bold">Checkout Pesanan</h1>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 rounded-xl border p-5">
        <h2 class="text-lg font-semibold">{{ $product->name }}</h2>

        <p class="mt-2">
            Harga grosir:
            Rp{{ number_format($product->wholesale_price, 0, ',', '.') }}
            / satuan
        </p>

        <p>
            Minimal pembelian: {{ $product->wholesale_min_qty }}
        </p>

        <p>
            Stok tersedia: {{ $product->stock }}
        </p>
    </div>

    <form action="{{ route('orders.store', $product) }}" method="POST"
          class="space-y-5">
        @csrf

        <div>
            <label for="quantity" class="mb-1 block font-medium">
                Jumlah pesanan
            </label>
            <input
                type="number"
                id="quantity"
                name="quantity"
                min="{{ $product->wholesale_min_qty }}"
                max="{{ $product->stock }}"
                value="{{ old('quantity', $product->wholesale_min_qty) }}"
                required
                class="w-full rounded-lg border p-3"
            >
        </div>

        <div>
            <label for="delivery_address" class="mb-1 block font-medium">
                Alamat pengiriman
            </label>
            <textarea
                id="delivery_address"
                name="delivery_address"
                rows="3"
                required
                class="w-full rounded-lg border p-3"
            >{{ old('delivery_address') }}</textarea>
        </div>

        <div>
            <label for="notes" class="mb-1 block font-medium">
                Catatan (opsional)
            </label>
            <textarea
                id="notes"
                name="notes"
                rows="2"
                class="w-full rounded-lg border p-3"
            >{{ old('notes') }}</textarea>
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-green-700 px-5 py-3 font-semibold text-white hover:bg-green-800"
        >
            Buat Pesanan
        </button>
    </form>
</div>
@endsection
