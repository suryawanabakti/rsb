# Penjelasan Kodingan: LabResultController (Pasien)

Dokumen ini menjelaskan fungsi-fungsi pada `LabResultController` untuk peran Pasien.

---

## 1. Menampilkan Daftar Hasil Lab Milik Pasien

```php
public function index()
{
    $patient = Auth::user()->patient;

    if (!$patient) {
        return redirect()->route('pasien.dashboard')->with('error', 'Data pasien tidak ditemukan.');
    }

    $results = LabResult::where('patient_id', $patient->id)
        ->with(['validator'])
        ->latest()
        ->paginate(10);

    return view('pasien.lab-results.index', compact('results'));
}
```

📌 **Isolasi Data Pasien**
`where('patient_id', $patient->id)`

**Artinya:**
Pasien **hanya bisa melihat hasil lab miliknya sendiri**. Sistem menyaring data berdasarkan ID pasien yang login, sehingga tidak mungkin melihat data milik pasien lain.

👉 **Jadi:**
Privasi medis pasien terjaga sepenuhnya karena setiap pasien memiliki "ruang" datanya masing-masing.

---

## 2. Melihat Detail Hasil Lab

```php
public function show($id)
{
    $patient = Auth::user()->patient;

    if (!$patient) {
        abort(404);
    }

    $labResult = LabResult::where('id', $id)
        ->where('patient_id', $patient->id)
        ->with(['validator', 'inputter'])
        ->firstOrFail();

    return view('pasien.lab-results.show', compact('labResult'));
}
```

📌 **Pengaman Detail**
`->where('id', $id)->where('patient_id', $patient->id)`

**Artinya:**
Fungsi ini memiliki dua lapis pengaman:
1. Mencari berdasarkan `id` hasil lab yang diklik.
2. **Sekaligus** memastikan hasil lab tersebut memang milik pasien yang sedang login.

**Penjelasan:**
Jika seorang pasien mencoba mengakses hasil lab milik pasien lain dengan mengganti angka ID di URL (contoh: `/lab-results/999`), sistem akan langsung memunculkan halaman error 404 (tidak ditemukan), karena `patient_id`-nya tidak cocok.

👉 **Jadi:**
Sistem ini tahan terhadap percobaan manipulasi URL untuk mengintip data lab pasien lain.
