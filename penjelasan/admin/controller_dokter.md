# Penjelasan Kodingan: DokterController (Kelola Dokter)

Dokumen ini menjelaskan fungsi-fungsi dalam `DokterController` yang digunakan Admin untuk mengelola data akun dokter.

---

## 1. Menampilkan & Mencari Dokter

```php
public function index(Request $request)
{
    $query = User::where('role', 'dokter');

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('nrp', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        });
    }

    $dokters = $query->latest()->paginate(15);
    return view('admin.dokters.index', compact('dokters'));
}
```

📌 **Fungsi Utama**
`public function index(Request $request)`

**Artinya:**
Method untuk menampilkan daftar semua akun dokter yang terdaftar di sistem.

**Penjelasan:**
- `User::where('role', 'dokter')` → Filter hanya mengambil user yang memiliki peran sebagai dokter.
- `if ($request->filled('search'))` → Jika admin mengetik sesuatu di kotak pencarian.
- `latest()->paginate(15)` → Mengurutkan dari pendaftar terbaru dan membagi data (15 dokter per halaman).

👉 **Jadi:**
Admin bisa mencari dokter dengan sangat fleksibel, baik lewat nama, username, nomor HP, NRP, bahkan alamat.

---

## 2. Menambahkan Dokter Baru

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'phone' => 'nullable|string|max:20',
        'nrp' => 'nullable|string|max:50',
        'address' => 'nullable|string',
        'sip_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($request->hasFile('sip_file')) {
        $validated['sip_file'] = $request->file('sip_file')->store('sip', 'public');
    }

    $validated['role'] = 'dokter';
    User::create($validated);

    return redirect()->route('admin.dokters.index')->with('success', 'Data dokter berhasil ditambahkan.');
}
```

📌 **Pendaftaran & Upload File**
`$request->hasFile('sip_file')`

**Penjelasan:**
- Jika admin mengunggah dokumen SIP (Surat Izin Praktek), file tersebut akan disimpan secara otomatis di folder `storage/app/public/sip`.
- `unique:users` → Memastikan username tidak boleh sama dengan user lain di sistem.
- `confirmed` → Memastikan password yang diketik dua kali (password & konfirmasi) harus sama.

👉 **Jadi:**
Fungsi ini membuat akun baru untuk dokter sekaligus menyimpan dokumen resmi SIP mereka dengan aman.

---

## 3. Menghapus Akun Dokter

```php
public function destroy(User $dokter)
{
    if ($dokter->role !== 'dokter') {
        abort(404);
    }

    $dokter->delete();
    return redirect()->route('admin.dokters.index')->with('success', 'Data dokter berhasil dihapus.');
}
```

📌 **Keamanan Penghapusan**
`if ($dokter->role !== 'dokter') { abort(404); }`

**Penjelasan:**
Ini adalah pengaman agar fungsi ini benar-benar hanya bisa menghapus user yang berperan sebagai 'dokter', bukan admin atau pasien (mencegah salah hapus lewat URL).

👉 **Jadi:**
Admin dapat menghapus akun dokter dengan aman, dan sistem akan memproteksi jika ada upaya menghapus role lain secara tidak sengaja.
