# Penjelasan Kodingan: LetterRequestController (Permohonan Surat)

Dokumen ini menjelaskan fungsi-fungsi dalam `LetterRequestController` yang mengelola permohonan surat keterangan (seperti SKBN) dari pasien.

---

## 1. Menampilkan & Memfilter Permohonan

```php
public function index(Request $request)
{
    $query = LetterRequest::with(['patient.user', 'letterType']);

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('patient.user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%");
        })->orWhereHas('patient', function ($q) use ($search) {
            $q->where('nik', 'like', "%{$search}%");
        });
    }

    $requests = $query->latest()->paginate(10);
    return view('admin.letter-requests.index', compact('requests'));
}
```

📌 **Fitur Filter & Pencarian**
`if ($request->filled('status'))`

**Penjelasan:**
- Admin bisa memfilter permohonan berdasarkan status (misal: hanya melihat yang 'pending' atau 'verified').
- Pencarian sangat mendalam: bisa mencari nama pasien, usernamenya, atau NIK-nya lewat relasi table.

👉 **Jadi:**
Halaman ini memudahkan admin untuk memantau permohonan mana saja yang perlu segera diproses.

---

## 2. Memperbarui Status & Notifikasi

```php
public function updateStatus(Request $request, LetterRequest $letterRequest)
{
    $request->validate([
        'status' => 'required|in:verified,approved,rejected,completed',
        'admin_notes' => 'nullable|string',
    ]);

    $letterRequest->update([
        'status' => $request->status,
        'admin_notes' => $request->admin_notes,
        'processed_at' => now(),
        'processed_by' => auth()->id(),
    ]);

    // Kirim notifikasi ke pasien
    $letterRequest->patient->user->notify(new \App\Notifications\LetterRequestStatusChanged($letterRequest));

    return back()->with('success', 'Status permohonan berhasil diperbarui.');
}
```

📌 **Alur Kerja Admin**
`$letterRequest->update([...])`

**Penjelasan:**
- Admin mengubah status permohonan dan bisa menambahkan catatan (admin_notes) jika ditolak atau perlu revisi.
- `processed_at` → Mencatat otomatis kapan permohonan diproses.
- `notify()` → **Sangat Penting!** Baris ini otomatis mengirim notifikasi ke dashboard/akun pasien agar mereka tahu status surat mereka berubah.

👉 **Jadi:**
Proses ini tidak hanya mengubah data di database, tapi juga langsung memberitahu pasien secara otomatis.

---

## 3. Upload Surat Final

```php
public function uploadFinalLetter(Request $request, LetterRequest $letterRequest)
{
    $request->validate([
        'final_letter' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    if ($request->hasFile('final_letter')) {
        $path = $request->file('final_letter')->store('final-letters', 'public');
        $letterRequest->update([
            'final_letter' => $path,
            'status' => 'completed',
        ]);
    }

    return back()->with('success', 'Surat final berhasil diunggah.');
}
```

📌 **Penyelesaian Permohonan**
`$letterRequest->update(['status' => 'completed'])`

**Penjelasan:**
- Ketika surat fisik sudah diproses dan di-scan, admin mengunggah file tersebut.
- Begitu file berhasil diunggah, status otomatis berubah menjadi **SELESAI (completed)**.

👉 **Jadi:**
Setelah tahap ini, pasien sudah bisa mendownload surat hasil permohonan mereka dalam format PDF atau Gambar.
