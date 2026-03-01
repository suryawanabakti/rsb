# Penjelasan Kodingan: ReportController (Pimpinan)

Dokumen ini menjelaskan fungsi pada `ReportController` untuk peran Pimpinan.

---

## 1. Menampilkan Laporan dengan Filter Tanggal

```php
public function index(Request $request)
{
    $type      = $request->get('type', 'letter_requests');
    $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
    $endDate   = $request->get('end_date', now()->endOfMonth()->toDateString());

    $data = [];
    if ($type === 'letter_requests') {
        $data = LetterRequest::with(['patient.user', 'letterType'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()->paginate(20);
    } else {
        $data = LabResult::with(['patient.user', 'validator'])
            ->whereBetween('test_date', [$startDate, $endDate])
            ->latest()->paginate(20);
    }

    return view('pimpinan.reports.index', compact('data', 'type', 'startDate', 'endDate'));
}
```

📌 **Fungsi Utama**
`public function index(Request $request)`

**Artinya:**
Fungsi ini menghasilkan laporan dinamis yang bisa dikustomisasi oleh Pimpinan berdasarkan rentang tanggal dan jenis data.

📌 **Nilai Default Otomatis**
`$startDate = $request->get('start_date', now()->startOfMonth()->toDateString())`

**Penjelasan:**
Jika Pimpinan belum memilih tanggal, sistem secara cerdas menampilkan data **bulan ini** (dari tanggal 1 sampai hari terakhir bulan ini) sebagai tampilan awal yang informatif.

📌 **Dua Jenis Laporan**
`if ($type === 'letter_requests') { ... } else { ... }`

**Penjelasan:**
Pimpinan dapat beralih antara dua jenis laporan:

| Jenis Laporan | Filter Tanggal | Isi |
|---|---|---|
| `letter_requests` | `created_at` (tanggal pengajuan) | Daftar permohonan surat |
| `lab_results` | `test_date` (tanggal pemeriksaan) | Daftar hasil lab |

- `whereBetween(...)` → Menyaring data agar hanya yang jatuh di antara tanggal mulai dan tanggal akhir yang dipilih.
- `paginate(20)` → Data laporan dibagi 20 baris per halaman agar mudah dibaca.

👉 **Jadi:**
Pimpinan bisa mencetak atau melihat laporan aktivitas untuk periode apapun yang diinginkan, baik untuk permohonan surat maupun hasil laboratorium.
