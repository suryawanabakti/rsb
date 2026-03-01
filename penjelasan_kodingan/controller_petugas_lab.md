# Penjelasan Kodingan: PetugasLabController (Kelola Petugas Lab)

Dokumen ini menjelaskan fungsi-fungsi dalam `PetugasLabController` yang digunakan Admin untuk mengelola akun Petugas Laboratorium.

---

## 1. Menampilkan & Mencari Petugas Lab

```php
public function index(Request $request)
{
    $query = User::where('role', 'petugas_lab');

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

    $petugasLabs = $query->latest()->paginate(15);
    return view('admin.petugas-lab.index', compact('petugasLabs'));
}
```

📌 **Penyaringan Peran (Role)**
`User::where('role', 'petugas_lab')`

**Artinya:**
Membatasi data agar yang muncul hanya user dengan peran 'petugas_lab', bukan dokter atau admin lainnya.

**Penjelasan:**
- Sama seperti pada controller dokter, pencarian dilakukan ke berbagai kolom sekaligus (name, username, phone, nrp, address).
- `paginate(15)` → Menjaga performa website tetap cepat meskipun data petugas lab sudah sangat banyak.

👉 **Jadi:**
Admin dapat memantau siapa saja petugas lab yang aktif dan mencari mereka dengan cepat jika diperlukan.

---

## 2. Pendaftaran Petugas Lab

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'phone' => 'nullable|string|max:20',
        'nrp' => 'nullable|string|max:50',
        'address' => 'nullable|string',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $validated['role'] = 'petugas_lab';
    User::create($validated);

    return redirect()->route('admin.petugas-labs.index')->with('success', 'Data petugas lab berhasil ditambahkan.');
}
```

📌 **Penetapan Role Otomatis**
`$validated['role'] = 'petugas_lab';`

**Penjelasan:**
Meskipun admin tidak memilih role di form (karena form ini khusus petugas lab), sistem secara cerdas langsung menetapkan role tersebut sebelum data disimpan ke database.

👉 **Jadi:**
Hanya dengan mengisi form nama dan password, sistem secara otomatis mengenali user tersebut sebagai Petugas Lab.

---

## 3. Update Password Opsional

```php
public function update(Request $request, User $petugasLab)
{
    // ... validasi ...
    
    if (empty($validated['password'])) {
        unset($validated['password']);
    }

    $petugasLab->update($validated);

    return redirect()->route('admin.petugas-labs.index')->with('success', 'Data petugas lab berhasil diperbarui.');
}
```

📌 **Fleksibilitas Update**
`if (empty($validated['password'])) { unset(...); }`

**Penjelasan:**
Logika ini sangat berguna. Jika admin hanya ingin mengubah alamat atau nama petugas tapi membiarkan password tetap lama, admin cukup mengosongkan kolom password di form. Sistem tidak akan mengubah password yang sudah ada.

👉 **Jadi:**
Admin tidak perlu repot menanyakan password lama atau membuat password baru setiap kali ingin mengedit profil petugas lab.
