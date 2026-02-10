<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::orderBy('start_date', 'desc')->get();
        return view('dashboard.admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $types = Schedule::getTypes();
        return view('dashboard.admin.schedules.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:pengajuan_kelompok,upload_proposal,revisi_1,revisi_2,revisi_3',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'description' => 'nullable|string'
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Schedule $schedule)
    {
        $types = Schedule::getTypes();
        return view('dashboard.admin.schedules.edit', compact('schedule', 'types'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'type' => 'required|in:pengajuan_kelompok,upload_proposal,revisi_1,revisi_2,revisi_3',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'description' => 'nullable|string'
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }

    public function toggleStatus(Schedule $schedule)
    {
        $schedule->update([
            'is_active' => !$schedule->is_active
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Status jadwal berhasil diubah!');
    }
}

