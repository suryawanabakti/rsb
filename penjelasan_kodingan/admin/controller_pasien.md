# Penjelasan Kodingan: PatientController (CRUD Pasien)

Dokumen ini menjelaskan fungsi-fungsi utama dalam `PatientController` yang digunakan untuk mengelola data pasien di sistem.

---

## 1. Menampilkan Daftar Pasien

```php
public function index(Request $request)
{
    $query = Patient::with('user');

    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%");
        })->orWhere('nik', 'like', "%{$search}%");
    }

    $patients = $query->latest()->paginate(15);

    return view('admin.patients.index', compact('patients'));
}
```

📌 **Fungsi Utama**
`public function index(Request $request)`

**Artinya:**
Ini adalah method (fungsi) untuk menampilkan halaman utama daftar pasien di panel admin.

**Penjelasan:**
- `$query = Patient::with('user');` → Menyiapkan pengambilan data dari tabel `patients` beserta data profilnya di tabel `users` (Eager Loading).
- `if ($request->filled('search'))` → Mengecek apakah ada kata kunci pencarian yang diketik oleh admin.
- `whereHas('user', ...)` → Mencari pasien berdasarkan **Nama** atau **Username** yang ada di tabel user.
- `orWhere('nik', ...)` → Juga mencari berdasarkan **NIK** pasien.
- `$query->latest()->paginate(15);` → Mengurutkan dari yang terbaru dan membagi tampilan menjadi 15 data per halaman.
- `return view(...)` → Mengirim data tersebut ke file tampilan (Blade) untuk ditampilkan ke layar.

👉 **Jadi:**
Halaman ini menampilkan semua pasien secara rapi dengan fitur pencarian yang canggih (bisa cari nama, username, atau NIK).

---

## 2. Menampilkan Detail Pasien

```php
public function show(Patient $patient)
{
    $patient->load(['user', 'letterRequests.letterType']);
    return view('admin.patients.show', compact('patient'));
}
```

📌 **Fungsi Utama**
`public function show(Patient $patient)`

**Artinya:**
Method ini digunakan untuk melihat profil lengkap dan riwayat seorang pasien secara spesifik.

**Penjelasan:**
- `$patient` → Menggunakan *Route Model Binding*, Laravel otomatis mencari data pasien berdasarkan ID yang diklik.
- `$patient->load(...)` → Mengambil data tambahan secara instan, yaitu data user dan riwayat permohonan surat (`letterRequests`) beserta jenis suratnya.
- `return view(...)` → Membuka halaman detail pasien.

👉 **Jadi:**
Saat admin mengklik tombol "Lihat", fungsi ini akan mengumpulkan semua informasi terkait pasien tersebut agar bisa dibaca di satu halaman.

---

## 3. Memperbarui Informasi Tambahan

```php
public function updateExtraInfo(Request $request, Patient $patient)
{
    $validated = $request->validate([
        'pangkat' => 'nullable|string|max:255',
        'nrp_nip' => 'nullable|string|max:50',
        'pendidikan_terakhir' => 'nullable|string|max:255',
        'jabatan_kesatuan' => 'nullable|string|max:255',
        'address' => 'required|string',
    ]);

    $patient->update($validated);

    return back()->with('success', 'Informasi tambahan pasien berhasil diperbarui.');
}
```

📌 **Fungsi Utama**
`public function updateExtraInfo(Request $request, Patient $patient)`

**Artinya:**
Method ini digunakan untuk update (memperbarui) data tambahan pasien seperti pangkat, NIP, atau alamat.

**Penjelasan:**
- `$request` → Berisi data baru yang dikirim dari form input.
- `$patient` → Data pasien yang akan diubah (otomatis dicari oleh Laravel).

📌 **Validasi Data**
`$request->validate([...])`

**Penjelasan:**
Laravel mengecek apakah data dari form valid atau tidak. Jika tidak valid, sistem otomatis kembali ke halaman sebelumnya dengan pesan error.

**Detail Aturan:**
- `nullable` → Boleh dikosongkan jika tidak ada data.
- `string` → Harus berupa teks/karakter.
- `max:255` → Panjang maksimal teks adalah 255 karakter.
- `required` → Wajib diisi (tidak boleh kosong).

👉 **Jadi:**
Semua field (pangkat, NIP, pendidikan, jabatan) boleh kosong, tetapi **Alamat (address)** wajib diisi.

📌 **Update ke Database**
`$patient->update($validated);`

**Penjelasan:**
Data yang sudah lolos tahap validasi langsung disimpan ke dalam database untuk menggantikan data lama.

📌 **Selesai & Kembali**
`return back()->with(...)`

**Penjelasan:**
Sistem akan mengembalikan admin ke halaman sebelumnya dengan memunculkan notifikasi berwarna hijau bahwa data berhasil diperbarui.
