<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Permohonan Kenaikan Pangkat</h1>
            <p class="text-sm text-gray-500">Senarai permohonan anda.</p>
        </div>

        <div>
            @if($canCreate)
                <a href="{{ route('app.permohonan.create') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-orange-600 text-white hover:bg-orange-700">
                    Permohonan Baharu
                </a>
            @else
                <button disabled class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-400 text-white cursor-not-allowed">
                    Permohonan Baharu
                </button>
            @endif
        </div>
    </div>

    <!-- table / empty state -->
</div>
