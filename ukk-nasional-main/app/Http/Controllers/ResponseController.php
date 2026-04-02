<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Response;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'response' => 'required|string',
        ]);

        $complaint = \App\Models\Complaint::findOrFail($validated['complaint_id']);

        // Limit to one response
        if ($complaint->responses()->count() > 0) {
            return redirect()->back()->with('error', 'Tanggapan sudah ada untuk pengaduan ini!');
        }

        Response::create([
            'complaint_id' => $validated['complaint_id'],
            'user_id' => Auth::id(),
            'date' => now()->toDateString(),
            'response' => $validated['response'],
        ]);

        // Jika status masih pending, ubah ke processing
        if ($complaint->status === 'pending') {
            $complaint->update(['status' => 'processing']);
        }

        return redirect()->back()->with('success', 'Tanggapan berhasil dikirim!');
    }
    public function destroy($id)
    {
        try {
            $response = Response::findOrFail($id);
            $response->delete();
            return redirect()->back()->with('success', 'Tanggapan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus tanggapan: ' . $e->getMessage());
        }
    }
}
