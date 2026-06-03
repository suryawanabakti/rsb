@extends('layouts.pasien')

@section('title', 'Detail Permohonan')

@section('content')
    <div class="mb-8">
        <a href="{{ route('pasien.letter-requests.index') }}"
            class="text-slate-500 hover:text-slate-700 text-sm mb-4 inline-block">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-slate-900">Detail Permohonan</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-lg">Informasi Surat</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Jenis Surat</p>
                            <p class="font-semibold text-slate-900 mt-1">{{ $letterRequest->letterType->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Tanggal Pengajuan</p>
                            <p class="font-semibold text-slate-900 mt-1">
                                {{ $letterRequest->created_at->isoFormat('D MMMM Y, H:i') }} WITA</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Status</p>
                            <span
                                class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold
                                @if ($letterRequest->status == 'submitted' || $letterRequest->status == 'pending') bg-amber-100 text-amber-700 
                                @elseif($letterRequest->status == 'approved' || $letterRequest->status == 'completed') bg-emerald-100 text-emerald-700
                                @elseif($letterRequest->status == 'rejected') bg-red-100 text-red-700
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ strtoupper($letterRequest->status) }}
                            </span>
                        </div>
                    </div>

                    @if ($letterRequest->notes)
                        <div class="pt-4 border-t border-slate-50">
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-2">Catatan Anda</p>
                            <p class="text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm">
                                {{ $letterRequest->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-lg">Dokumen Lampiran</h3>
                </div>
                <div class="p-6 space-y-3">
                    @forelse($letterRequest->files as $file)
                        <div class="flex items-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-2xl mr-4">📎</span>
                            <div class="flex-grow overflow-hidden">
                                <p class="font-bold text-slate-900 text-sm truncate">{{ $file->file_name }}</p>
                                <p class="text-xs text-slate-500">{{ number_format($file->file_size / 1024, 0) }} KB</p>
                            </div>
                            <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                class="text-blue-600 font-bold text-sm bg-blue-50 px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors">
                                Lihat
                            </a>
                        </div>
                    @empty
                        <p class="text-slate-500 text-sm italic">Tidak ada lampiran.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right Sidebar -->
        <div class="space-y-6">
            @if (in_array($letterRequest->status, ['approved', 'completed', 'verified']))
                <div class="bg-indigo-600 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="font-bold text-lg mb-2">Unduh Surat</h3>
                    <p class="text-indigo-100 text-sm mb-6">Surat Anda telah selesai diproses. Silakan unduh dokumen Word.</p>
                    <a href="{{ route('pasien.letter-requests.download-word', $letterRequest->id) }}"
                        class="flex items-center justify-center space-x-2 w-full bg-white text-indigo-600 font-bold py-4 rounded-xl shadow-lg hover:bg-indigo-50 transition-all text-sm uppercase tracking-widest">
                        <span>📥</span>
                        <span>Unduh Word</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
