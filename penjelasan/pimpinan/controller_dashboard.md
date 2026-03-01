# Penjelasan Kodingan: DashboardController (Pimpinan)

Dokumen ini menjelaskan fungsi pada `DashboardController` untuk peran Pimpinan.

---

## 1. Menampilkan Ringkasan Statistik Rumah Sakit

```php
public function index()
{
    $stats = [
        'total_patients'          => Patient::count(),
        'total_letter_requests'   => LetterRequest::count(),
        'pending_letter_requests' => LetterRequest::where('status', 'pending')->count(),
        'total_lab_results'       => LabResult::count(),
        'validated_lab_results'   => LabResult::where('status', 'validated')->count(),
    ];

    $recentRequests = LetterRequest::with(['patient.user', 'letterType'])
        ->latest()->take(5)->get();

    $recentLabResults = LabResult::with(['patient.user'])
        ->latest()->take(5)->get();

    return view('pimpinan.dashboard', compact('stats', 'recentRequests', 'recentLabResults'));
}
```

📌 **Fungsi Utama**
`public function index()`

**Artinya:**
Dashboard Pimpinan memberikan **pandangan menyeluruh (bird's eye view)** atas seluruh aktivitas rumah sakit, tanpa batasan kepemilikan seperti pada peran lain.

**Penjelasan:**
Statistik yang dikumpulkan adalah angka **global keseluruhan sistem**:

| Key | Artinya |
|---|---|
| `total_patients` | Total pasien terdaftar di sistem |
| `total_letter_requests` | Total permohonan surat dari seluruh pasien |
| `pending_letter_requests` | Permohonan yang masih menunggu diproses |
| `total_lab_results` | Total hasil pemeriksaan lab |
| `validated_lab_results` | Hasil lab yang sudah divalidasi dokter |

- `->take(5)->get()` → Menampilkan 5 transaksi terbaru untuk surat dan lab, sebagai gambaran aktivitas terkini.

👉 **Jadi:**
Pimpinan dapat memantau "denyut nadi" operasional rumah sakit secara real-time: berapa pasien aktif, berapa surat yang antri, dan bagaimana kinerja validasi lab.
