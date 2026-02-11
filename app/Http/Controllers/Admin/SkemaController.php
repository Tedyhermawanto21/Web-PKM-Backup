<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Skema;

class SkemaController extends Controller
{
    public function index()
    {
        $skemas = Skema::all();
        return view('dashboard.admin.skemas.index', compact('skemas'));
    }

    public function create()
    {
        return view('dashboard.admin.skemas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:skemas,nama',
            'label' => 'required',
            'warna' => 'nullable',
            'panduan' => 'nullable|file|mimes:pdf,doc,docx|max:5120'
        ]);

        $data = $request->all();

        if ($request->hasFile('panduan')) {
            $file = $request->file('panduan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('panduan_skema', $filename, 'public');
            $data['panduan'] = $path;
        }

        Skema::create($data);

        return redirect()->route('admin.skemas.index')->with('success', 'Skema berhasil ditambahkan.');
    }

    public function edit(Skema $skema)
    {
        return view('dashboard.admin.skemas.edit', compact('skema'));
    }

    public function update(Request $request, Skema $skema)
    {
        $request->validate([
            'nama' => 'required|unique:skemas,nama,' . $skema->id,
            'label' => 'required',
            'warna' => 'nullable',
            'panduan' => 'nullable|file|mimes:pdf,doc,docx|max:5120'
        ]);

        $data = $request->all();

        if ($request->hasFile('panduan')) {
            // Delete old file if exists
            if ($skema->panduan && \Illuminate\Support\Facades\Storage::disk('public')->exists($skema->panduan)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($skema->panduan);
            }
            
            $file = $request->file('panduan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('panduan_skema', $filename, 'public');
            $data['panduan'] = $path;
        } else {
            unset($data['panduan']);
        }

        $skema->update($data);

        return redirect()->route('admin.skemas.index')->with('success', 'Skema berhasil diperbarui.');
    }

    public function destroy(Skema $skema)
    {
        if ($skema->panduan && \Illuminate\Support\Facades\Storage::disk('public')->exists($skema->panduan)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($skema->panduan);
        }
        $skema->delete();
        return redirect()->route('admin.skemas.index')->with('success', 'Skema berhasil dihapus.');
    }
}
