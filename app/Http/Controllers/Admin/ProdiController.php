<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::latest()->get();
        return view('dashboard.admin.prodis.index', compact('prodis'));
    }

    public function create()
    {
        return view('dashboard.admin.prodis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:prodis,name',
            'code' => 'nullable|string|max:50|unique:prodis,code',
            'fakultas' => 'required|string|max:255',
        ]);

        Prodi::create($validated);

        return redirect()->route('admin.prodis.index')->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi)
    {
        return view('dashboard.admin.prodis.edit', compact('prodi'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:prodis,name,' . $prodi->id,
            'code' => 'nullable|string|max:50|unique:prodis,code,' . $prodi->id,
            'fakultas' => 'required|string|max:255',
        ]);

        $prodi->update($validated);

        return redirect()->route('admin.prodis.index')->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();

        return redirect()->route('admin.prodis.index')->with('success', 'Program Studi berhasil dihapus.');
    }
}
