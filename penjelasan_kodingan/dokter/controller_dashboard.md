# Penjelasan Kodingan: DashboardController (Dokter)

Dokumen ini menjelaskan fungsi pada `DashboardController` untuk peran Dokter.

---

## 1. Menampilkan Halaman Dashboard

```php
public function index()
{
    $user = Auth::user();

    $stats = [
        'pending_validation' => LabResult::where('status', 'pending')->count(),
        'validated_by_me'    => LabResult::where('validated_by', $user->id)->count(),
        'total_results'      => LabResult::count(),
    ];

    $days = ['minggu','senin','selasa','rabu','kamis','jumat','sabtu'];
    $today = $days[now()->dayOfWeek];

    $todaySchedules = DoctorSchedule::where('doctor_id', $user->id)
        ->where('day_of_week', $today)
        ->where('is_active', true)
        ->get();

    $pendingResults = LabResult::with(['patient.user', 'inputter'])
        ->where('status', 'pending')
        ->latest()->take(5)->get();

    return view('dokter.dashboard', compact('stats', 'todaySchedules', 'pendingResults'));
}
```

📌 **Fungsi Utama**
`public function index()`

**Artinya:**
Method ini mengumpulkan semua data yang dibutuhkan untuk ditampilkan di halaman utama (dashboard) seorang dokter.

**Penjelasan:**

📌 **Statistik Utama**
`$stats = [...]`

| Key | Artinya |
|---|---|
| `pending_validation` | Jumlah hasil lab di seluruh sistem yang belum divalidasi (bukan hanya milik dokter ini) |
| `validated_by_me` | Jumlah hasil lab yang sudah divalidasi oleh dokter yang sedang login |
| `total_results` | Total semua hasil lab di sistem |

📌 **Jadwal Hari Ini**
`$today = $days[now()->dayOfWeek]`

**Penjelasan:**
- `now()->dayOfWeek` → Mengambil angka hari ini (0=Minggu, 1=Senin, dst.).
- Angka tersebut dicocokkan dengan array `$days` untuk mendapatkan nama hari dalam Bahasa Indonesia.
- Hasilnya digunakan untuk menampilkan jadwal praktek dokter khusus untuk hari ini saja.

📌 **Antrean Validasi**
`$pendingResults = LabResult::where('status', 'pending')...take(5)->get()`

**Penjelasan:**
Mengambil 5 hasil lab yang paling baru dan statusnya masih menunggu validasi, untuk ditampilkan sebagai daftar tugas cepat bagi dokter.

👉 **Jadi:**
Dashboard dokter memberi tiga informasi sekaligus: angka statistik keseluruhan, jadwal praktek hari ini, dan daftar hasil lab yang perlu segera divalidasi.
