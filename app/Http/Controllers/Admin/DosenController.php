<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index()
    {
        $role = Role::firstOrCreate(['name' => 'dosen']);
        $users = User::where('role_id', $role->id)->latest()->get();
        return view('dashboard.admin.dosens.index', compact('users'));
    }

    public function create()
    {
        $prodis = Prodi::all();
        return view('dashboard.admin.dosens.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nidn' => 'required|string|max:20|unique:nomor_induks,value',
            'program_studi' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $role = Role::firstOrCreate(['name' => 'dosen']);

        $nomorInduk = \App\Models\NomorInduk::create([
            'value' => $data['nidn'],
            'type' => 'nidn',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['nidn'] . '@uhamka.ac.id',
            'nomor_induk_id' => $nomorInduk->id,
            'program_studi' => $data['program_studi'],
            'password' => Hash::make($data['password']),
            'role_id' => $role->id,
        ]);

        return redirect()->route('admin.dosens.index')->with('success', 'Akun Dosen berhasil dibuat.');
    }

    public function edit(User $dosen)
    {
        // Ensure user is actually a dosen
        if (!$dosen->isDosen()) {
            abort(404);
        }
        $prodis = Prodi::all();
        return view('dashboard.admin.dosens.edit', ['user' => $dosen, 'prodis' => $prodis]);
    }

    public function update(Request $request, User $dosen)
    {
        // Ensure user is actually a dosen
        if (!$dosen->isDosen()) {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nidn' => 'required|string|max:20|unique:nomor_induks,value,' . $dosen->nomor_induk_id,
            'program_studi' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $dosen->name = $data['name'];
        $dosen->program_studi = $data['program_studi'];

        // Update Nomor Induk
        if ($dosen->nomorInduk) {
            $dosen->nomorInduk->update([
                'value' => $data['nidn']
            ]);
            // Also update email if it was based on NIDN? Maybe better to leave email as identifier.
            // But spec says email is NIDN@uhamka.ac.id. Let's update it to keep consistency.
            $dosen->email = $data['nidn'] . '@uhamka.ac.id';
        } else {
            // Create if missing (edge case)
            $nomorInduk = \App\Models\NomorInduk::create([
                'value' => $data['nidn'],
                'type' => 'nidn',
            ]);
            $dosen->nomor_induk_id = $nomorInduk->id;
            $dosen->email = $data['nidn'] . '@uhamka.ac.id';
        }

        if (!empty($data['password'])) {
            $dosen->password = Hash::make($data['password']);
        }
        $dosen->save();

        return redirect()->route('admin.dosens.index')->with('success', 'Akun Dosen berhasil diperbarui.');
    }

    public function destroy(User $dosen)
    {
        if (!$dosen->isDosen()) {
            abort(404);
        }
        $dosen->delete();
        return redirect()->route('admin.dosens.index')->with('success', 'Akun Dosen berhasil dihapus.');
    }
}
