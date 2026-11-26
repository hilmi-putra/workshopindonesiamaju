<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SubmissionsExport;
use App\Http\Controllers\Controller;
use App\Models\ManuscriptSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class SubmissionController extends Controller
{
    public function index(Request $request): View
    {
        $query = ManuscriptSubmission::latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = trim((string) $request->search);
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('author_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $submissions = $query->paginate(15)->withQueryString();
        $statuses = ManuscriptSubmission::statuses();

        return view('admin.submissions.index', compact('submissions', 'statuses'));
    }

    public function updateStatus(Request $request, ManuscriptSubmission $submission): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,reviewing,accepted,rejected'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $submission->update($validated);

        return back()->with('success', "Status naskah \"{$submission->title}\" diperbarui.");
    }

    public function download(ManuscriptSubmission $submission)
    {
        if (!Storage::disk('public')->exists($submission->file_path)) {
            return back()->with('error', 'File naskah tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $submission->file_path,
            $submission->original_filename ?: basename($submission->file_path)
        );
    }

    public function export(Request $request)
    {
        $submissions = ManuscriptSubmission::latest()
            ->when($request->filled('status') && $request->status !== 'all', fn ($q) => $q->where('status', $request->status))
            ->get();

        return Excel::download(new SubmissionsExport($submissions), 'pengajuan_naskah.xlsx');
    }
}
