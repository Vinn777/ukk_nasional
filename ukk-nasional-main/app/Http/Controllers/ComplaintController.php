<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Complaint;
use App\Models\Response;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('complaints', 'public');
            $validated['photo'] = 'storage/' . $path;
        }

        Complaint::create([
            'user_id' => Auth::id(),
            'date' => now()->toDateString(),
            'title' => $validated['title'],
            'location' => $validated['location'],
            'description' => $validated['description'],
            'photo' => $validated['photo'] ?? null,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Aduan berhasil dikirim!');
    }

    public function updateStatus(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        
        // Pastikan hanya ada satu tanggapan
        if ($request->filled('response') && $complaint->responses()->count() > 0) {
            return redirect()->back()->with('error', 'Tanggapan sudah ada! Hapus tanggapan lama terlebih dahulu.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,resolved',
            'response' => 'nullable|string'
        ]);

        $complaint->update(['status' => $validated['status']]);

        // Hapus tanggapan otomatis jika status diubah kembali ke 'pending'
        if ($validated['status'] === 'pending') {
            $complaint->responses()->delete();
        }

        // Simpan ke tabel responses jika ada tanggapan baru
        if ($request->filled('response')) {
            Response::create([
                'complaint_id' => $complaint->id,
                'user_id' => Auth::id(),
                'date' => now()->toDateString(),
                'response' => $validated['response'],
            ]);
        }

        $message = $validated['status'] === 'pending' 
                   ? 'Status diubah ke Pending & Tanggapan telah dihapus!' 
                   : 'Status pengaduan berhasil diperbarui!';

        return redirect()->back()->with('success', $message);
    }

    public function report()
    {
        $complaints = Complaint::with(['user', 'responses.user'])->latest()->get();
        return view('admin.report', compact('complaints'));
    }
    public function destroy($id)
    {
        try {
            $complaint = Complaint::findOrFail($id);

            // Manual cleanup of responses to avoid foreign key issues
            $complaint->responses()->delete();

            if ($complaint->photo) {
                $photoPath = str_replace('storage/', 'public/', $complaint->photo);
                \Illuminate\Support\Facades\Storage::delete($photoPath);
            }

            $complaint->delete();
            return redirect()->back()->with('success', 'Pengaduan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pengaduan: ' . $e->getMessage());
        }
    }
}
