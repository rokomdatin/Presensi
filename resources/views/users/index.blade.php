@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-brand-biru">Manajemen User</h1>
        <p class="text-gray-600">Kelola akun pengguna sistem</p>
    </div>
    <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-merah text-white rounded-lg hover:bg-brand-merah/90 transition">
        <i class="fas fa-plus"></i>
        Tambah User
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form action="{{ route('users.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent"
                   placeholder="Cari username, nama, atau email...">
        </div>
        <div>
            <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent">
                <option value="">Semua Role</option>
                @foreach(['admin', 'kepegawaian', 'keuangan', 'guest'] as $role)
                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-brand-biru text-white rounded-lg hover:bg-brand-biru/90 transition">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-brand-biru text-white">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Username</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Lengkap</th>
                    <th class="px-4 py-3 text-left font-semibold">Email</th>
                    <th class="px-4 py-3 text-left font-semibold">Role</th>
                    <th class="px-4 py-3 text-left font-semibold">NIP</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Login Terakhir</th>
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($users as $u)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $u->username }}</td>
                    <td class="px-4 py-3">{{ $u->nama_lengkap }}</td>
                    <td class="px-4 py-3">{{ $u->email ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $roleColor = match($u->role) {
                                'admin' => 'red',
                                'kepegawaian' => 'blue',
                                'keuangan' => 'green',
                                default => 'gray'
                            };
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $roleColor }}-100 text-{{ $roleColor }}-700 capitalize">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td class="px-4 py-3 font-mono text-xs">{{ $u->nip ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $u->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $u->last_login?->format('d M Y H:i') ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('users.edit', $u->id) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($u->id !== auth()->id())
                            <form action="{{ route('users.toggle-status', $u->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-2 {{ $u->is_active ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg transition" title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="fas {{ $u->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada data user</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="px-4 py-3 border-t">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
