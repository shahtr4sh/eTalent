<?php

namespace App\Livewire\App\Permohonan;

use Livewire\Component;

class Show extends Component
{
    public function render()
    {
        return view('livewire.permohonan.show')
            ->layout('layouts.app');
    }
}
