<?php

namespace App\Livewire\App\Profile;

use Livewire\Component;
use App\Models\Pemohon;

class ShowProfile extends Component
{
    public $pemohon;

    public function mount()
    {
        $user = auth()->user();

        logger()->info('APP PROFILE DEBUG', [
            'user_id' => $user?->id,
            'email' => $user?->email,
            'staff_id' => $user?->staff_id,
        ]);

        $this->pemohon = \App\Models\Pemohon::find($user?->staff_id);

        // Akademik
        $this->pemohon = \App\Models\Pemohon::with(['akademikStaf' => function ($q) {
            $q->orderByDesc('tahun_tamat')->orderByDesc('kod_tahap');
        }])
            ->where('staff_id', auth()->user()->staff_id)
            ->first();

        // Jawatan and Jabatan
        $this->pemohon = \App\Models\Pemohon::with([
            'jabatanStaf',
            'jawatanStaf' => function ($q) {
                $q->orderByDesc('terkini')
                    ->orderByDesc('aktif');
            },
            'jawatanStafTerkini',
        ])
            ->where('staff_id', auth()->user()->staff_id)
            ->first();
    }

    public function render()
    {
        return view('livewire.app.profile.show-profile');
    }
}
