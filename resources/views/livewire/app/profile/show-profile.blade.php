<div>
    <h1><strong>Profil Staf</strong></h1>
    @if($pemohon)
        <p><strong>Staff ID:</strong> {{ $pemohon->staff_id }}</p>
        <p><strong>Nama:</strong> {{ $pemohon->nama }}</p>
        <p><strong>Emel:</strong> {{ $pemohon->emel_rasmi }}</p>

        <p><strong>Unit:</strong>
            {{ $pemohon->jabatanStaf->namaunit ?? '-' }}
            ({{ $pemohon->jabatanStaf->kod_unit ?? '-' }})
        </p>

        <p><strong>Jabatan:</strong>
            {{ $pemohon->jabatanStaf->nama_jabatan ?? '-' }}
            ({{ $pemohon->jabatanStaf->kod_jabatan ?? '-' }})
        </p>

        <p><strong>Jawatan Semasa:</strong>
            {{ $pemohon->jawatanStafTerkini->nama_jawatan ?? '-' }}
            ({{ $pemohon->jawatanStafTerkini->kod_jawatan ?? '-' }})
        </p>

        <p><strong>Gred Semasa:</strong>
            {{ $pemohon->jawatanStafTerkini->gred_jawatan ?? '-' }}
        </p>
    @endif

    <div>
        <br>
        <p><strong>Rekod Jawatan Staf</strong></p>

        <br><hr>

        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-neutral-200">
                            <thead>
                            <tr class="text-neutral-500">
                                <th class="px-5 py-3 text-sm font-medium text-left uppercase">Jawatan</th>
                                <th class="px-5 py-3 text-sm font-medium text-left uppercase">Gred</th>
                                <th class="px-5 py-3 text-sm font-medium text-left uppercase">Status</th>
                            </tr>
                            </thead>
                            @foreach($pemohon->jawatanStaf as $j)
                                <tbody class="divide-y divide-neutral-200">
                                <tr class="text-neutral-800">
                                    <td class="px-5 py-4 text-xs font-medium whitespace-nowrap">{{ $j->nama_jawatan ?? '-' }}</td>
                                    <td class="px-5 py-4 text-xs whitespace-nowrap">{{ $j->gred_jawatan ?? '-' }}</td>
                                    <td class="px-5 py-4 text-xs whitespace-nowrap">{{ $j->terkini ? 'Aktif' : 'Tidak Aktif' }}</td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <br>
        <p><strong>Rekod Akademik Staf</strong></p>
        <br><hr>
        @if($pemohon->akademikStaf->isEmpty())
            <p style="text-indent: 40px;">Tiada rekod akademik.</p>
        @else
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-neutral-200">
                                <thead>
                                <tr class="text-neutral-500">
                                    <th class="px-5 py-3 text-sm font-medium text-left uppercase">Tahap Akademik</th>
                                    <th class="px-5 py-3 text-sm font-medium text-left uppercase">Tahun Tamat</th>
                                    <th class="px-5 py-3 text-sm font-medium text-left uppercase">Bidang</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-200">
                                @foreach($pemohon->akademikStaf as $a)
                                    <tr class="text-neutral-800">
                                        <td class="px-5 py-4 text-xs font-medium whitespace-nowrap">{{ $a->tahap_akademik ?? '—' }}</td>
                                        <td class="px-5 py-4 text-xs whitespace-nowrap">{{ $a->tahun_tamat ?? '—' }}</td>
                                        <td class="px-5 py-4 text-xs whitespace-nowrap">{{ $a->kod_bidang ?? '—' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
