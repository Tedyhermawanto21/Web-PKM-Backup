<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Fetch proposal IDs from pivot to ensure assigned proposals are returned
        $proposalIds = \App\Models\ProposalReviewer::where('reviewer_id', $user->id)->pluck('proposal_id')->unique();

        $assigned = [];
        if ($proposalIds->count()) {
            $assigned = \App\Models\Proposal::whereIn('id', $proposalIds)
                ->with(['ketua'])
                ->orderByRaw("FIELD(id, " . $proposalIds->implode(',') . ")")
                ->get();
        } else {
            $assigned = collect();
        }
        return view('dashboard.reviewer.assigned', compact('assigned'));
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['ketua', 'anggota', 'reviewers']);
        return view('dashboard.reviewer.show', compact('proposal'));
    }

    public function submit(Request $request, Proposal $proposal)
    {
        $user = Auth::user();

        $data = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable|string|max:5000'
        ]);

        // Update pivot
        $proposal->reviewers()->updateExistingPivot($user->id, [
            'status' => 'reviewed',
            'score' => $data['score'],
            'comments' => $data['comments'] ?? null,
            'updated_at' => now(),
        ]);

        // After submitting, check if all assigned reviewers have completed their reviews
        $assigned = $proposal->reviewers()->withPivot('status')->get();
        $notReviewed = $assigned->filter(function ($r) {
            return ($r->pivot->status ?? null) !== 'reviewed';
        });

        try {
            if ($notReviewed->count() === 0) {
                // All reviewers finished — mark proposal for admin confirmation
                $proposal->update(['status_admin' => 'menunggu_konfirmasi_admin']);
            } else {
                // Still waiting other reviewers
                $proposal->update(['status_admin' => 'sedang_direview']);
            }
        } catch (\Throwable $e) {
            // don't fail the review submission if status update fails
            \Log::warning('Failed to update proposal status_admin after reviewer submit: ' . $e->getMessage());
        }

        return redirect()->route('reviewer.assigned.index')->with('success', 'Review submitted.');
    }
}
