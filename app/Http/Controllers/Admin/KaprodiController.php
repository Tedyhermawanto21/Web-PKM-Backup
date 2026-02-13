<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\NomorInduk;

class KaprodiController extends Controller
{
    public function index()
    {
        $role = Role::where('name', 'kaprodi')->first();
        $users = $role ? User::where('role_id', $role->id)->latest()->get() : collect([]);
        return view('dashboard.admin.kaprodis.index', compact('users'));
    }

    public function create()
    {
        $prodis = Prodi::all();
        return view('dashboard.admin.kaprodis.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nidn' => 'required|string|max:20|unique:nomor_induks,value',
            'program_studi' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $role = Role::firstOrCreate(['name' => 'kaprodi']);

        $nomorInduk = NomorInduk::create([
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

        return redirect()->route('admin.kaprodis.index')->with('success', 'Akun Kaprodi berhasil dibuat.');
    }

    public function edit(User $kaprodi)
    {
        // Ensure user is actually a kaprodi
        if (!$kaprodi->isKaprodi()) {
            abort(404);
        }
        $prodis = Prodi::all();
        return view('dashboard.admin.kaprodis.edit', ['user' => $kaprodi, 'prodis' => $prodis]);
    }

    public function update(Request $request, User $kaprodi)
    {
        // Ensure user is actually a kaprodi
        if (!$kaprodi->isKaprodi()) {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nidn' => 'required|string|max:20|unique:nomor_induks,value,' . $kaprodi->nomor_induk_id,
            'program_studi' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $kaprodi->name = $data['name'];
        $kaprodi->program_studi = $data['program_studi'];

        // Update Nomor Induk
         if ($kaprodi->nomorInduk) {
            $kaprodi->nomorInduk->update(['value' => $data['nidn']]);
        } else {
             $nomorInduk = NomorInduk::create([
                'value' => $data['nidn'],
                'type' => 'nidn',
            ]);
            $kaprodi->nomor_induk_id = $nomorInduk->id;
        }
        
        $kaprodi->email = $data['nidn'] . '@uhamka.ac.id';

        if (!empty($data['password'])) {
            $kaprodi->password = Hash::make($data['password']);
        }

        $kaprodi->save();

        return redirect()->route('admin.kaprodis.index')->with('success', 'Akun Kaprodi berhasil diperbarui.');
    }

    public function destroy(User $kaprodi)
    {
        if (!$kaprodi->isKaprodi()) {
            abort(404);
        }
        
        // Delete related NomorInduk if exists
        if ($kaprodi->nomorInduk) {
            $kaprodi->nomorInduk->delete();
        }
        
        $kaprodi->delete();
        return redirect()->route('admin.kaprodis.index')->with('success', 'Akun Kaprodi berhasil dihapus.');
    }
}
