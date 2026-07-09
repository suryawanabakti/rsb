@extends('layouts.pasien')

@section('title', 'Buat Permohonan')

@section('content')
    <div class="mb-8">
        <a href="{{ route('pasien.letter-requests.index') }}"
            class="text-slate-500 hover:text-slate-700 text-sm mb-4 inline-block">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-slate-900">Buat Permohonan Baru</h1>
        <p class="text-slate-500">Ajukan permohonan surat keterangan baru</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-2xl">
        <form action="{{ route('pasien.letter-requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label for="letter_type_id" class="block text-sm font-bold text-slate-700 mb-2">Jenis Surat</label>
                <select name="letter_type_id" id="letter_type_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer bg-white">
                    <option value="" disabled selected>Pilih jenis surat...</option>
                    @foreach ($letterTypes as $type)
                        <option value="{{ $type->id }}" data-slug="{{ $type->slug }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6" id="photo-section" style="display: none;">
                <label for="photo_4x6" class="block text-sm font-bold text-slate-700 mb-2">Pas Foto 4x6
                    (Wajib - Format JPG/PNG, Maks 2MB)</label>
                <div class="relative border-2 border-dashed border-blue-300 rounded-xl p-8 text-center hover:bg-blue-50 transition-colors cursor-pointer bg-blue-50/30"
                    onclick="document.getElementById('photo_4x6').click()">
                    <input type="file" name="photo_4x6" id="photo_4x6" accept=".jpg,.jpeg,.png" required
                        class="hidden" onchange="updatePhotoName(this)">
                    <span class="text-3xl mb-2 block">📷</span>
                    <p class="text-sm font-bold text-blue-600">Klik untuk upload pas foto 4x6</p>
                    <p class="text-xs text-slate-400 mt-1" id="photo-name">Belum ada file dipilih</p>
                </div>
                @error('photo_4x6')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="files" class="block text-sm font-bold text-slate-700 mb-2">Dokumen Pendukung
                    (Optional - PDF/Gambar)</label>
                <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:bg-slate-50 transition-colors cursor-pointer"
                    onclick="document.getElementById('files').click()">
                    <input type="file" name="files[]" id="files" accept=".pdf,.jpg,.jpeg,.png" multiple
                        class="hidden" onchange="updateFileNames(this)">
                    <span class="text-3xl mb-2 block">📄</span>
                    <p class="text-sm font-bold text-slate-600">Klik untuk upload file</p>
                    <p class="text-xs text-slate-400 mt-1" id="file-names">Bisa upload lebih dari satu file (Maks 5MB/file)
                    </p>
                </div>
                @error('files')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @error('files.*')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <script>
                document.getElementById('letter_type_id').addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    const slug = selected.dataset.slug;
                    const photoSection = document.getElementById('photo-section');
                    const photoInput = document.getElementById('photo_4x6');
                    if (slug === 'skbn') {
                        photoSection.style.display = 'block';
                        photoInput.required = true;
                    } else {
                        photoSection.style.display = 'none';
                        photoInput.required = false;
                    }
                });

                function updateFileNames(input) {
                    const fileNames = Array.from(input.files).map(file => file.name).join(', ');
                    document.getElementById('file-names').textContent = fileNames ||
                        'Bisa upload lebih dari satu file (Maks 5MB/file)';
                }

                function updatePhotoName(input) {
                    const name = input.files.length > 0 ? input.files[0].name : 'Belum ada file dipilih';
                    document.getElementById('photo-name').textContent = name;
                }
            </script>

            <div class="mb-6">
                <label for="keperluan" class="block text-sm font-bold text-slate-700 mb-2">Keperluan (Tujuan)</label>
                <textarea name="keperluan" id="keperluan" rows="2" required placeholder="Contoh: Assesment Jabatan Kapolres"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all"></textarea>
                @error('keperluan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="notes" class="block text-sm font-bold text-slate-700 mb-2">Catatan Tambahan
                    (Opsional)</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 outline-none transition-all"></textarea>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                Kirim Permohonan
            </button>
        </form>
    </div>
@endsection
