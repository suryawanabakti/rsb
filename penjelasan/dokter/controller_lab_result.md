# Penjelasan Kodingan: LabResultController (Dokter)

Dokumen ini menjelaskan fungsi-fungsi pada `LabResultController` untuk peran Dokter.

---

## 1. Menampilkan & Memfilter Hasil Lab

```php
public function index(Request $request)
{
    $query = LabResult::with(['patient.user', 'inputter', 'validator']);

    if ($request->get('status') === 'pending') {
        $query->where('status', 'pending');
    } elseif ($request->get('status') === 'validated') {
        $query->where('status', 'validated');
    }

    $results = $query->latest()->paginate(15);
    $currentFilter = $request->get('status', 'all');

    return view('dokter.lab-results.index', compact('results', 'currentFilter'));
}
```

📌 **Filter Berdasarkan Status**
`if ($request->get('status') === 'pending')`

**Artinya:**
Dokter bisa menyaring tampilan daftar hasil lab, misalnya hanya melihat yang masih `pending` atau sudah `validated`. Berbeda dengan Petugas Lab, dokter bisa melihat **semua** hasil lab milik semua pasien karena tugas dokter adalah memvalidasi semuanya.

👉 **Jadi:**
Dokter memiliki tampilan menyeluruh tanpa batasan kepemilikan, namun bisa memfokuskan pandangan ke data yang perlu ditindaklanjuti.

---

## 2. Memvalidasi Hasil Lab

```php
public function validate(Request $request, $id)
{
    $result = LabResult::where('status', 'pending')->findOrFail($id);

    $result->update([
        'status'       => 'validated',
        'validated_by' => Auth::id(),
        'validated_at' => now(),
    ]);

    // Notifikasi ke pasien
    $result->patient->user->notify(new LabResultStatusUpdated($result, 'validated'));

    return redirect()->route('dokter.lab-results.index')
        ->with('success', 'Hasil pemeriksaan berhasil divalidasi.');
}
```

📌 **Fungsi Utama: Validasi**
`->update(['status' => 'validated', ...])`

**Artinya:**
Ini adalah inti dari tugas Dokter terhadap data laboratorium. Dengan menekan tombol validasi, dokter memberikan persetujuan resmi atas data tersebut.

**Penjelasan:**
- `where('status', 'pending')->findOrFail($id)` → Pengaman: dokter hanya bisa memvalidasi data yang memang masih berstatus pending. Jika datanya sudah divalidasi atau ID-nya salah, sistem langsung memunculkan error 404.
- `validated_by` → Menyimpan ID dokter yang melakukan validasi sebagai rekam jejak.
- `validated_at` → Menyimpan waktu validasi secara otomatis.
- `notify(...)` → Begitu dokter memvalidasi, pasien langsung menerima notifikasi di dashboardnya bahwa hasil lab sudah bisa dilihat.

👉 **Jadi:**
Proses validasi hanya butuh satu klik dokter, namun mencatat siapa, kapan, dan langsung memberi tahu pasien secara otomatis.
