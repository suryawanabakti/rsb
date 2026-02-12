@extends('layouts.admin')

@section('title', 'Edit Hasil Pemeriksaan')

@section('content')
    <div class="mb-8">
        <a href="{{ route('petugas-lab.lab-results.index') }}"
            class="text-emerald-600 font-semibold hover:underline text-sm">← Kembali ke Daftar</a>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Edit Hasil Pemeriksaan</h1>
        <p class="text-slate-500">Perbarui data hasil pemeriksaan laboratorium</p>
    </div>

    <form action="{{ route('petugas-lab.lab-results.update', $result->id) }}" method="POST" class="space-y-6"
        x-data="labForm()">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-100">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-5">
            <h3 class="text-lg font-bold text-slate-900 mb-2">Informasi Dasar</h3>

            <!-- Patient Search -->
            <div class="relative">
                <label class="block text-sm font-bold text-slate-700 mb-1">Pasien <span
                        class="text-red-500">*</span></label>
                <input type="hidden" name="patient_id" x-model="patientId" required>
                <div x-show="patientId"
                    class="flex items-center justify-between bg-emerald-50 px-4 py-3 rounded-xl border border-emerald-200">
                    <span class="text-emerald-800 font-bold" x-text="patientName"></span>
                    <button type="button" @click="clearPatient()"
                        class="text-emerald-600 hover:text-emerald-800 font-bold">✕</button>
                </div>
                <div x-show="!patientId">
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="searchPatients()"
                        @focus="searchPatients()" placeholder="Klik untuk pilih pasien atau ketik nama..."
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                    <div x-show="patients.length > 0"
                        class="absolute z-10 w-full bg-white border border-slate-200 rounded-xl mt-1 shadow-lg max-h-48 overflow-y-auto">
                        <template x-for="patient in patients" :key="patient.id">
                            <button type="button" @click="selectPatient(patient)"
                                class="w-full text-left p-3 hover:bg-slate-50 border-b border-slate-50 transition-colors">
                                <p class="font-bold text-slate-900" x-text="patient.user?.name"></p>
                                <p class="text-xs text-slate-400" x-text="'NIK: ' + patient.nik"></p>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Test Name -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nama Pemeriksaan <span
                        class="text-red-500">*</span></label>
                <input type="text" name="test_name" value="{{ old('test_name', $result->test_name) }}" required
                    placeholder="Cth: Darah Lengkap, Urinalisis, Gula Darah"
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
            </div>

            <!-- Test Date -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Tanggal Pemeriksaan <span
                        class="text-red-500">*</span></label>
                <input type="date" name="test_date" value="{{ old('test_date', $result->test_date) }}" required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
            </div>
        </div>

        <!-- Result Data -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-900">Data Hasil Pemeriksaan</h3>
                <button type="button" @click="addRow()"
                    class="text-sm bg-emerald-100 text-emerald-700 font-bold px-4 py-2 rounded-xl hover:bg-emerald-200 transition-colors">
                    + Tambah Parameter
                </button>
            </div>

            <template x-for="(row, index) in rows" :key="index">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 mb-3">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400" x-text="'Parameter #' + (index + 1)"></span>
                        <button type="button" x-show="rows.length > 1" @click="removeRow(index)"
                            class="text-red-500 hover:text-red-700 text-sm font-bold">🗑️ Hapus</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="text" :name="'result_data[' + index + '][name]'" x-model="row.name" required
                            placeholder="Nama parameter (cth: Hemoglobin)"
                            class="px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <input type="text" :name="'result_data[' + index + '][value]'" x-model="row.value" required
                            placeholder="Nilai"
                            class="px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <input type="text" :name="'result_data[' + index + '][unit]'" x-model="row.unit"
                            placeholder="Satuan (cth: g/dL)"
                            class="px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <input type="text" :name="'result_data[' + index + '][normal_range]'" x-model="row.normal_range"
                            placeholder="Nilai normal (cth: 13.0-17.0)"
                            class="px-3 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            </template>
        </div>

        <!-- Notes -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <label class="block text-sm font-bold text-slate-700 mb-1">Catatan Tambahan</label>
            <textarea name="notes" rows="3" placeholder="Catatan opsional..."
                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">{{ old('notes', $result->notes) }}</textarea>
        </div>

        <!-- Submit -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('petugas-lab.lab-results.show', $result->id) }}"
                class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors shadow-sm text-lg">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center px-8 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-sm text-lg">
                💾 Simpan Perubahan
            </button>
        </div>
    </form>

    <script>
        function labForm() {
            return {
                patientId: '{{ old('patient_id', $result->patient_id) }}',
                patientName: '{{ $result->patient->user->name ?? 'Pasien' }}',
                searchQuery: '',
                patients: [],
                rows: @json(old('result_data', $result->result_data)),

                async searchPatients() {
                    // Fetch recent/all if empty, or search if typed
                    try {
                        const res = await fetch('{{ route('petugas-lab.lab-results.search-patients') }}?q=' +
                            encodeURIComponent(this.searchQuery));
                        this.patients = await res.json();
                    } catch (e) {
                        console.error('Search error:', e);
                    }
                },

                selectPatient(patient) {
                    this.patientId = patient.id;
                    this.patientName = patient.user?.name || 'NIK: ' + patient.nik;
                    this.patients = [];
                    this.searchQuery = '';
                },

                clearPatient() {
                    this.patientId = '';
                    this.patientName = '';
                    this.searchPatients(); // Show list again
                },

                addRow() {
                    this.rows.push({
                        name: '',
                        value: '',
                        unit: '',
                        normal_range: ''
                    });
                },

                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    }
                }
            };
        }
    </script>
@endsection
