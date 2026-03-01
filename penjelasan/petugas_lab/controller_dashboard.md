# Penjelasan Kodingan: DashboardController (Petugas Lab)

Dokumen ini menjelaskan fungsi pada `DashboardController` milik Petugas Lab.

---

## 1. Menampilkan Halaman Dashboard

```php
public function index()
{
    $user = Auth::user();

    $stats = [
        'total_inputted' => LabResult::where('inputted_by', $user->id)->count(),
        'pending_validation' => LabResult::where('inputted_by', $user->id)->where('status', 'pending')->count(),
        'validated' => LabResult::where('inputted_by', $user->id)->where('status', 'validated')->count(),
    ];

    $recentResults = LabResult::with(['patient.user', 'validator'])
        ->where('inputted_by', $user->id)
        ->latest()
        ->take(5)
        ->get();

    return view('petugas-lab.dashboard', compact('stats', 'recentResults'));
}
```

📌 **Fungsi Utama**
`public function index()`

**Artinya:**
Method ini menampilkan halaman utama (dashboard) Petugas Lab yang berisi ringkasan statistik dan data hasil lab terbaru.

**Penjelasan:**
- `Auth::user()` → Mengambil data user yang sedang login (Petugas Lab yang aktif).
- `$stats = [...]` → Menghitung tiga angka statistik penting:

| Key | Artinya |
|---|---|
| `total_inputted` | Jumlah total hasil lab yang pernah diinput |
| `pending_validation` | Jumlah hasil lab yang sedang menunggu validasi dokter |
| `validated` | Jumlah hasil lab yang sudah divalidasi dokter |

- `where('inputted_by', $user->id)` → **Sangat Penting!** Ini memastikan setiap Petugas Lab hanya melihat data yang mereka sendiri input, bukan data milik petugas lain.
- `->take(5)->get()` → Hanya mengambil 5 hasil lab terbaru untuk ditampilkan di daftar singkat.

👉 **Jadi:**
Dashboard ini memberikan gambaran cepat kepada Petugas Lab: sudah berapa data yang diinput, berapa yang masih menunggu, dan berapa yang sudah selesai divalidasi.
