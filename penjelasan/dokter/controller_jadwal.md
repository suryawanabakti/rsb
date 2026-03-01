# Penjelasan Kodingan: ScheduleController (Dokter)

Dokumen ini menjelaskan fungsi pada `ScheduleController` untuk peran Dokter.

---

## 1. Menampilkan Jadwal Praktek Pribadi

```php
public function index()
{
    $schedules = DoctorSchedule::where('doctor_id', Auth::id())
        ->where('is_active', true)
        ->orderByRaw("FIELD(day_of_week, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
        ->get()
        ->groupBy('day_of_week');

    $days = ['minggu','senin','selasa','rabu','kamis','jumat','sabtu'];
    $today = $days[now()->dayOfWeek];

    return view('dokter.schedules.index', compact('schedules', 'today'));
}
```

📌 **Fungsi Utama**
`public function index()`

**Artinya:**
Menampilkan jadwal praktek milik dokter yang sedang login, diurutkan dan dikelompokkan berdasarkan hari.

**Penjelasan:**
- `where('doctor_id', Auth::id())` → Hanya menampilkan jadwal milik dokter yang aktif login, bukan jadwal dokter lain.
- `where('is_active', true)` → Hanya jadwal yang aktif yang ditampilkan.
- `orderByRaw("FIELD(day_of_week, 'senin', ...)")`  → *Teknik MySQL khusus* untuk mengurutkan hari sesuai urutan normal hari kerja (Senin–Minggu) dan bukan urutan alfabet.
- `->groupBy('day_of_week')` → Mengelompokkan jadwal berdasarkan hari sehingga tampilan di halaman bisa diatur per hari.

👉 **Jadi:**
Dokter dapat melihat jadwal praktek mereka sendiri yang tersusun rapi dari Senin sampai Minggu, beserta informasi hari ini sebagai penanda.
