@extends('layouts.app')

@section('title', 'Manajemen Pengguna - KopiTani')

@section('content')
<div class="card">
    <h1>Manajemen Pengguna</h1>
    <p>Daftar akun petani dan kedai yang terdaftar di KopiTani.</p>

    <div style="overflow-x: auto; margin-top: 24px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #244b35; color: white;">
                    <th style="padding: 12px; text-align: left;">No.</th>
                    <th style="padding: 12px; text-align: left;">Nama</th>
                    <th style="padding: 12px; text-align: left;">Email</th>
                    <th style="padding: 12px; text-align: left;">Role</th>
                    <th style="padding: 12px; text-align: left;">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px;">{{ $loop->iteration }}</td>
                        <td style="padding: 12px;">{{ $user->name }}</td>
                        <td style="padding: 12px;">{{ $user->email }}</td>
                        <td style="padding: 12px;">
                            {{ ucfirst($user->role) }}
                        </td>
                        <td style="padding: 12px;">
                            {{ $user->created_at?->format('d M Y') ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 20px; text-align: center;">
                            Belum ada pengguna petani atau kedai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p style="margin-top: 16px;">
        Total pengguna: <strong>{{ $users->count() }}</strong>
    </p>
</div>
@endsection