<div class="h-screen p-6 max-w-3xl mx-auto overflow-y-auto">
    <h1 class="text-2xl font-semibold mb-1">Permohonan Baharu</h1>
    <p class="text-sm text-gray-500 mb-6">Sila lengkapkan semua maklumat dan muat naik dokumen yang diperlukan.</p>

    {{-- NOTIFICATION (basic) --}}
    <script>
        window.addEventListener('notify', (e) => {
            alert((e.detail.type ?? 'info').toUpperCase() + ': ' + (e.detail.message ?? ''));
        });
    </script>

    <div class="bg-white rounded-xl border p-6 space-y-6">

        {{-- 1) PILIH JAWATAN (Dropdown) --}}
        <div>
            <label class="block text-sm font-medium mb-2">Jawatan yang ingin dipohon</label>

            {{-- ✅ HIGHLIGHT: Dropdown anda letak di sini --}}
            <select wire:model="selected_id_daftar" class="w-full border rounded-lg px-3 py-2">
                <option value="">-- Sila pilih --</option>
                @foreach($jawatanOptions as $opt)
                    <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                @endforeach
            </select>

            @error('selected_id_daftar')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- 2) BORANG KENAIKAN PANGKAT --}}
        <div>
            <div class="flex items-center justify-between">
                <label class="block text-sm font-medium mb-2">Borang Kenaikan Pangkat (PDF)</label>

                {{-- ✅ Link download template PDF (letak fail di public/forms/borang-kenaikan-pangkat.pdf) --}}
                <a href="{{ asset('forms/borang-kenaikan-pangkat.pdf') }}"
                   class="text-sm text-blue-700 underline" target="_blank">
                    Muat turun borang (PDF)
                </a>
            </div>

            <input type="file" wire:model="borang_pdf" accept="application/pdf"
                   class="w-full border rounded-lg px-3 py-2">

            @error('borang_pdf')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- 3) DOKUMEN WAJIB --}}
        <div>
            <label class="block text-sm font-medium mb-2">CV / Resume</label>
            <input type="file" wire:model="cv_resume" accept=".pdf,.doc,.docx"
                   class="w-full border rounded-lg px-3 py-2">
            @error('cv_resume')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Dokumen Sokongan</label>
            <input type="file" wire:model="dok_sokongan" accept=".pdf,.jpg,.jpeg,.png"
                   class="w-full border rounded-lg px-3 py-2">
            @error('dok_sokongan')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Dokumen Penilaian Prestasi</label>
            <input type="file" wire:model="penilaian_prestasi" accept=".pdf,.jpg,.jpeg,.png"
                   class="w-full border rounded-lg px-3 py-2">
            @error('penilaian_prestasi')
            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- SUBMIT --}}
        <div class="pt-2 flex gap-3">
            <a href="{{ route('app.permohonan.index') }}"
               class="px-4 py-2 rounded-lg border">
                Batal
            </a>

            <button
                wire:click="submit"
                wire:loading.attr="disabled"
                wire:target="submit,borang_pdf,cv_resume,dok_sokongan,penilaian_prestasi"
                class="px-4 py-2 rounded-lg bg-orange-600 text-white hover:bg-orange-700 disabled:opacity-50"
                @disabled(
                empty($selected_id_daftar)
                || empty($borang_pdf)
                || empty($cv_resume)
                || empty($dok_sokongan)
                || empty($penilaian_prestasi)
                )
            >
                <span wire:loading.remove wire:target="submit">Hantar Permohonan</span>
                <span wire:loading wire:target="submit">Memproses...</span>
            </button>
        </div>
    </div>
</div>
