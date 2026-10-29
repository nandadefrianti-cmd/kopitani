
@extends('layouts.app')

@section('title', 'Manajemen Kategori - KopiTani')

@section('content')
    <section class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 24px;">
            <div>
                <h1 style="margin: 0 0 8px;">Manajemen Kategori Kopi</h1>
                <p style="margin: 0; color: #6b7280;">
                    Kelola kategori produk dari petani lokal.
                </p>
            </div>

            <a
                href="{{ route('admin.categories.create') }}"
                style="background: #315d3d; color: white; padding: 12px 18px; border-radius: 8px; text-decoration: none; display: inline-block;"
            >
                + Tambah Kategori
            </a>
        </div>

        @if (session('success'))
            <p style="padding: 12px; background: #dcfce7; color: #166534; border-radius: 8px;">
                {{ session('success') }}
            </p>
        @endif

        @if (session('error'))
            <p style="padding: 12px; background: #fee2e2; color: #991b1b; border-radius: 8px;">
                {{ session('error') }}
            </p>
        @endif

        @if ($categories->isEmpty())
            <p>Belum ada kategori kopi.</p>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #f3f4f6;">
                            <th style="padding: 14px;">No.</th>
                            <th style="padding: 14px;">Nama Kategori</th>
                            <th style="padding: 14px;">Slug</th>
                            <th style="padding: 14px;">Jumlah Produk</th>
                            <th style="padding: 14px;">Deskripsi</th>
                            <th style="padding: 14px; min-width: 150px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categories as $category)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 14px;">
                                    {{ $loop->iteration }}
                                </td>

                                <td style="padding: 14px; font-weight: 600;">
                                    {{ $category->name }}
                                </td>

                                <td style="padding: 14px; color: #6b7280;">
                                    {{ $category->slug }}
                                </td>

                                <td style="padding: 14px;">
                                    {{ $category->products_count }} produk
                                </td>

                                <td style="padding: 14px; min-width: 180px;">
                                    {{ $category->description ?? '-' }}
                                </td>

                                <td style="padding: 14px;">
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <a
                                            href="{{ route('admin.categories.edit', $category) }}"
                                            style="background: #e5e7eb; color: #1f2937; padding: 8px 12px; border-radius: 6px; text-decoration: none;"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('admin.categories.destroy', $category) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                style="background: #dc2626; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer;"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
