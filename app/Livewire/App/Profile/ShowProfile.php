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
    }

    public function render()
    {
        return view('livewire.app.profile.show-profile');
    }
}
