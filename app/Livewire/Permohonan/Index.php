<?php

namespace App\Livewire\Permohonan;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.permohonan.index')
            ->layout('layouts.app');
    }
}
