# Penjelasan Kodingan: LabResultController (Petugas Lab)

Dokumen ini menjelaskan fungsi-fungsi dalam `LabResultController` milik Petugas Lab untuk mengelola hasil pemeriksaan laboratorium.

---

## 1. Menampilkan Daftar Hasil Lab

```php
public function index()
{
    $results = LabResult::with(['patient.user', 'validator'])
        ->where('inputted_by', Auth::id())
        ->latest()
        ->paginate(15);

    return view('petugas-lab.lab-results.index', compact('results'));
}
```

📌 **Fungsi Utama**
`public function index()`

**Penjelasan:**
- `where('inputted_by', Auth::id())` → Petugas Lab hanya bisa melihat hasil lab yang diinput oleh dirinya sendiri, bukan milik petugas lain. Ini menjaga privasi dan ketertiban data.
- `with(['patient.user', 'validator'])` → Sekaligus mengambil data nama pasien dan nama dokter yang memvalidasi, agar tidak perlu query berulang.

👉 **Jadi:**
Setiap Petugas Lab memiliki "inbox" data labnya masing-masing yang terpisah dari petugas lain.

---

## 2. Menambahkan Hasil Lab Baru

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'test_name' => 'required|string|max:255',
        'test_date' => 'required|date',
        'result_data' => 'required|array|min:1',
        'result_data.*.name' => 'required|string',
        'result_data.*.value' => 'required|string',
        'result_data.*.unit' => 'nullable|string',
        'result_data.*.normal_range' => 'nullable|string',
        'notes' => 'nullable|string',
    ]);

    $labResult = LabResult::create([
        ...
        'status' => 'pending',
        'inputted_by' => Auth::id(),
    ]);

    // Notify patient
    $labResult->patient->user->notify(...);

    // Notify all doctors
    Notification::send($doctors, new NewLabResultAdded($labResult));

    return redirect()->route('petugas-lab.lab-results.index')
        ->with('success', 'Hasil pemeriksaan berhasil disimpan.');
}
```

📌 **Validasi Data Array (Kompleks)**
`'result_data.*.name' => 'required|string'`

**Artinya:**
Hasil lab bisa terdiri dari banyak baris (contoh: Hemoglobin, Trombosit, Leukosit, dll). Notasi bintang `*` di sini berarti validasi berlaku untuk **setiap baris data** dalam array.

📌 **Status Otomatis: Pending**
`'status' => 'pending'`

**Penjelasan:**
Setiap data yang baru diinput langsung diberi status **pending** (menunggu), yang artinya data ini belum dapat diakses pasien sampai dokter memvalidasinya.

📌 **Notifikasi Otomatis**

**Penjelasan:**
Setelah data berhasil disimpan, sistem langsung mengirim dua notifikasi secara otomatis:
1. **Notifikasi ke Pasien** → Memberitahu bahwa ada hasil lab baru untuk mereka.
2. **Notifikasi ke semua Dokter** → Memberitahu bahwa ada data baru yang perlu divalidasi.

👉 **Jadi:**
Satu kali input dari Petugas Lab langsung menggerakkan seluruh sistem notifikasi ke pihak yang berkepentingan.

---

## 3. Mengedit Hasil Lab (Hanya Status Pending)

```php
public function edit($id)
{
    $result = LabResult::with('patient.user')
        ->where('inputted_by', Auth::id())
        ->where('status', 'pending')
        ->findOrFail($id);

    return view('petugas-lab.lab-results.edit', compact('result'));
}
```

📌 **Pengaman Edit Data**
`->where('status', 'pending')->findOrFail($id)`

**Artinya:**
Petugas Lab hanya boleh mengedit data yang **statusnya masih pending**. Jika data sudah divalidasi dokter, tidak bisa diubah lagi. Jika seseorang mencoba mengakses edit data yang sudah divalidasi lewat URL, sistem akan otomatis menampilkan error 404 (halaman tidak ditemukan).

👉 **Jadi:**
Ini melindungi integritas data medis agar data yang sudah divalidasi dokter tidak bisa diubah secara sembarangan.

---

## 4. Mencari Pasien (Pencarian Live / AJAX)

```php
public function searchPatients(Request $request)
{
    $search = $request->get('q');

    $query = Patient::with('user');

    if ($search) {
        $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })->orWhere('nik', 'like', "%{$search}%");
    } else {
        // Tampilkan pasien terbaru jika tidak ada kata kunci
        $query->latest()->take(10);
    }

    return response()->json($query->limit(20)->get());
}
```

📌 **API Pencarian Pasien**
`return response()->json(...)`

**Artinya:**
Fungsi ini tidak menampilkan halaman (view), tapi mengembalikan **data JSON**. Ini digunakan oleh form input agar Petugas Lab bisa mengetik nama pasien dan langsung muncul pilihan otomatis (autocomplete).

**Penjelasan:**
- Jika ada kata kunci (`$search`) → mencari berdasarkan nama atau NIK pasien.
- Jika tidak ada kata kunci → menampilkan 10 pasien yang paling baru terdaftar sebagai saran default.
- `limit(20)` → Membatasi hasil pencarian maksimal 20 data agar response tetap cepat.

👉 **Jadi:**
Fitur ini memudahkan Petugas Lab saat input data lab: cukup ketik nama pasien, langsung muncul pilihannya tanpa harus reload halaman.
