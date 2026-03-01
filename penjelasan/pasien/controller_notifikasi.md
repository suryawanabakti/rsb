# Penjelasan Kodingan: NotificationController (Pasien)

Dokumen ini menjelaskan fungsi-fungsi pada `NotificationController` untuk peran Pasien.

---

## 1. Menandai Satu Notifikasi sebagai Terbaca

```php
public function markAsRead($id)
{
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    if (isset($notification->data['letter_request_id'])) {
        return redirect()->route('pasien.letter-requests.show', $notification->data['letter_request_id']);
    }

    return back();
}
```

📌 **Fungsi Utama: Tandai & Arahkan**

**Artinya:**
Ketika pasien mengklik sebuah notifikasi, dua hal terjadi sekaligus:
1. Notifikasi ditandai sebagai sudah dibaca.
2. Pasien diarahkan langsung ke halaman yang relevan.

**Penjelasan:**
- `auth()->user()->notifications()->findOrFail($id)` → Mencari notifikasi berdasarkan ID, dan memastikannya milik user yang login (keamanan).
- `markAsRead()` → Mengisi kolom `read_at` dengan waktu sekarang di database.
- `if (isset($notification->data['letter_request_id']))` → Membaca "tujuan" dari notifikasi. Jika notifikasinya terkait surat, pasien langsung diarahkan ke halaman detail surat tersebut.

👉 **Jadi:**
Klik notifikasi bukan hanya menandai sudah dibaca, tapi juga langsung membawa pasien ke tempat yang tepat tanpa perlu mencari-cari.

---

## 2. Menandai Semua Notifikasi sebagai Terbaca

```php
public function markAllAsRead()
{
    auth()->user()->unreadNotifications->markAsRead();
    return back()->with('success', 'Semua notifikasi telah ditandai sebagai terbaca.');
}
```

📌 **Mass Update Notifikasi**
`->unreadNotifications->markAsRead()`

**Artinya:**
`unreadNotifications` adalah properti bawaan Laravel yang secara otomatis mengambil semua notifikasi yang belum dibaca. `markAsRead()` kemudian memperbarui semuanya sekaligus dalam satu perintah.

👉 **Jadi:**
Dengan satu klik "Tandai Semua Terbaca", seluruh daftar notifikasi bersih seketika tanpa harus satu per satu.
