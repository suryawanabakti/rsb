# Penjelasan Kodingan: DashboardController (Pasien)

Dokumen ini menjelaskan fungsi pada `DashboardController` untuk peran Pasien.

---

## 1. Menampilkan Halaman Dashboard

```php
public function index()
{
    $user = Auth::user();
    $patient = $user->patient;

    if (!$patient) {
        return view('pasien.dashboard', [
            'stats' => ['total' => 0, 'pending' => 0, 'processed' => 0, 'completed' => 0],
            'recentRequests' => collect([]),
        ]);
    }

    $requests = $patient->letterRequests()->latest()->get();

    $stats = [
        'total'     => $requests->count(),
        'pending'   => $requests->whereIn('status', ['submitted', 'pending'])->count(),
        'processed' => $requests->where('status', 'approved')->count(),
        'completed' => $requests->where('status', 'completed')->count(),
    ];

    return view('pasien.dashboard', [
        'stats'          => $stats,
        'recentRequests' => $requests->take(5),
        'patient'        => $patient
    ]);
}
```

📌 **Penanganan Data Aman**
`if (!$patient) { ... }`

**Artinya:**
Sebelum mengambil data, sistem mengecek dulu apakah pasien punya data profil. Ini adalah penanganan untuk situasi darurat (*edge case*) agar tampilan tidak error jika terjadi kejanggalan data.

📌 **Statistik Permohonan Surat**
`$stats = [...]`

**Penjelasan:**
Dashboard pasien menghitung status permohonan suratnya sendiri:

| Key | Artinya |
|---|---|
| `total` | Semua permohonan surat yang pernah dibuat |
| `pending` | Yang masih diproses (belum ada keputusan admin) |
| `processed` | Yang sudah disetujui admin |
| `completed` | Yang sudah selesai dan suratnya tersedia |

- `->take(5)` → Hanya 5 permohonan terbaru yang ditampilkan di daftar singkat dashboard.

👉 **Jadi:**
Pasien langsung bisa tahu status semua permohonan surat mereka hanya dari satu halaman dashboard.
