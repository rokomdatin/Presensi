<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Hanya admin yang dapat mengakses halaman ini.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $pegawaiList = Pegawai::active()
            ->whereNotIn('nip', User::whereNotNull('nip')->pluck('nip'))
            ->orderBy('nama_tanpa_gelar')
            ->get();

        return view('users.create', compact('pegawaiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'nama_lengkap' => 'required|string|max:255',
            'role' => 'required|in:admin,kepegawaian,keuangan,guest',
            'nip' => 'nullable|string|exists:pegawai,nip',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $user = User::create($validated);

        LogAktivitas::log('create', 'users', $user->id, null, [
            'username' => $user->username,
            'role' => $user->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $pegawaiList = Pegawai::active()
            ->where(function ($q) use ($user) {
                $q->whereNotIn('nip', User::whereNotNull('nip')->where('id', '!=', $user->id)->pluck('nip'))
                  ->orWhere('nip', $user->nip);
            })
            ->orderBy('nama_tanpa_gelar')
            ->get();

        return view('users.edit', compact('user', 'pegawaiList'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldValues = $user->toArray();

        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'nama_lengkap' => 'required|string|max:255',
            'role' => 'required|in:admin,kepegawaian,keuangan,guest',
            'nip' => 'nullable|string|exists:pegawai,nip',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $user->update($validated);

        LogAktivitas::log('update', 'users', $user->id, $oldValues, $validated);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $oldValues = $user->toArray();
        $user->delete();

        LogAktivitas::log('delete', 'users', $id, $oldValues, null);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "User berhasil {$status}.");
    }
}
