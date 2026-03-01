# Penjelasan Kodingan: DoctorScheduleController (Jadwal Dokter)

Dokumen ini menjelaskan fungsi-fungsi dalam `DoctorScheduleController` yang mengelola jadwal praktek dokter.

---

## 1. Menampilkan Daftar Jadwal

```php
public function index()
{
    $schedules = DoctorSchedule::with('doctor')->latest()->paginate(15);
    return view('admin.doctor-schedules.index', compact('schedules'));
}
```

📌 **Fungsi Utama**
`public function index()`

**Artinya:**
Method ini digunakan untuk menampilkan tabel daftar jadwal semua dokter di panel admin.

**Penjelasan:**
- `DoctorSchedule::with('doctor')` → Mengambil data jadwal beserta informasi dokternya (relasi) agar tidak terjadi query berulang-ulang.
- `latest()` → Mengurutkan jadwal berdasarkan yang terakhir ditambahkan.
- `paginate(15)` → Membatasi tampilan 15 data per halaman.

👉 **Jadi:**
Admin bisa melihat daftar jadwal dokter secara rapi dan teratur dengan sistem halaman.

---

## 2. Menambah Jadwal Baru

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'doctor_id' => 'required|exists:users,id',
        'day_of_week' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'room' => 'nullable|string|max:255',
        'is_active' => 'boolean',
    ]);

    if (!$request->has('is_active')) {
        $validated['is_active'] = false;
    }

    DoctorSchedule::create($validated);

    return redirect()->route('admin.doctor-schedules.index')
        ->with('success', 'Jadwal dokter berhasil ditambahkan.');
}
```

📌 **Validasi Data**
`$request->validate([...])`

**Penjelasan:**
Sistem mengecek data yang diinput admin sebelum disimpan:
- `exists:users,id` → Memastikan ID dokter benar-benar ada di tabel users.
- `in:senin,selasa...` → Memastikan pilihan hari sesuai dengan hari yang tersedia.
- `after:start_time` → Memastikan jam selesai harus **setelah** jam mulai (mencegah salah input).

📌 **Penyimpanan**
`DoctorSchedule::create($validated);`

**Penjelasan:**
Data yang valid disimpan ke database, lalu admin diarahkan kembali ke halaman daftar dengan pesan sukses.

👉 **Jadi:**
Fungsi ini menjamin jadwal yang dibuat masuk akal (jam tidak terbalik) dan tersimpan dengan benar.

---

## 3. Menghapus Jadwal

```php
public function destroy(DoctorSchedule $doctorSchedule)
{
    $doctorSchedule->delete();
    return redirect()->route('admin.doctor-schedules.index')
        ->with('success', 'Jadwal dokter berhasil dihapus.');
}
```

📌 **Fungsi Utama**
`public function destroy(DoctorSchedule $doctorSchedule)`

**Artinya:**
Digunakan untuk menghapus jadwal dokter yang sudah tidak berlaku.

**Penjelasan:**
- `$doctorSchedule` → Laravel otomatis mencari data jadwal yang ingin dihapus.
- `delete()` → Perintah untuk menghapus data tersebut dari database.

👉 **Jadi:**
Admin dapat dengan mudah membersihkan jadwal lama atau salah input hanya dengan satu klik.
