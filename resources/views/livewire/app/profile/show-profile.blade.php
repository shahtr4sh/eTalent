<div>
    <h2>Profil Pemohon</h2>

    @if($pemohon)
        <p><strong>Staff ID:</strong> {{ $pemohon->staff_id }}</p>
        <p><strong>Nama:</strong> {{ $pemohon->nama }}</p>
        <p><strong>Emel:</strong> {{ $pemohon->emel_rasmi }}</p>
        <p><strong>Jabatan:</strong> {{ $pemohon->jabatan }}</p>
    @else
        <p>Profil tidak dijumpai.</p>
    @endif
</div>
