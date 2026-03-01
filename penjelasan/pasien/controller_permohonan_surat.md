# Penjelasan Kodingan: LetterRequestController (Pasien)

Dokumen ini menjelaskan fungsi-fungsi pada `LetterRequestController` untuk peran Pasien saat mengajukan permohonan surat.

---

## 1. Arsitektur: Menggunakan Service Layer

```php
public function __construct(
    protected LetterRequestService $letterRequestService
) {}
```

📌 **Dependency Injection & Service Layer**
`protected LetterRequestService $letterRequestService`

**Artinya:**
Controller ini tidak mengerjakan logika bisnis sendiri. Semua logika (membuat permohonan, mengambil data) didelegasikan ke `LetterRequestService`. Ini adalah pola desain yang baik agar kode lebih rapi dan mudah diuji.

---

## 2. Menampilkan Daftar Permohonan

```php
public function index()
{
    $patient = Auth::user()->patient;
    $requests = $this->letterRequestService->getPatientRequests($patient->id);

    return view('pasien.letter-requests.index', compact('requests'));
}
```

📌 **Delegasi ke Service**
`$this->letterRequestService->getPatientRequests(...)`

**Penjelasan:**
Controller hanya mengambil ID pasien lalu menyerahkan tugas pengambilan data ke `LetterRequestService`. Controller tetap ramping dan bersih.

---

## 3. Mengajukan Permohonan Baru

```php
public function store(StoreLetterRequest $request)
{
    $patient = Auth::user()->patient;

    try {
        $this->letterRequestService->createRequest($patient->id, $request->validated());

        return redirect()->route('pasien.letter-requests.index')
            ->with('success', 'Permohonan surat berhasil dikirim.');
    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
    }
}
```

📌 **Validasi Otomatis via Form Request**
`store(StoreLetterRequest $request)`

**Artinya:**
Berbeda dengan controller lain yang memanggil `$request->validate()` secara manual, controller ini menggunakan kelas `StoreLetterRequest` khusus. Validasi sudah berjalan **otomatis** sebelum fungsi ini dieksekusi.

📌 **Penanganan Error (try-catch)**
`try { ... } catch (\Exception $e) { ... }`

**Penjelasan:**
Ada lapisan penanganan kesalahan di sini. Jika proses pembuatan permohonan gagal karena alasan apapun (misalnya koneksi database terputus), sistem tidak akan crash, melainkan mengembalikan pasien ke form dengan pesan error yang ramah.

👉 **Jadi:**
Pasien terlindungi dari halaman error yang menyeramkan. Jika gagal, mereka cukup dikembalikan ke form untuk mencoba lagi.
