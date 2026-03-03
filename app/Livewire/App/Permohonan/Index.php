<?php

namespace App\Livewire\App\Permohonan;

use Livewire\Component;
use App\Models\PromotionApplication;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $applications = [];
    public bool $canCreate = true;

    // Definisi "aktif" (selari dengan polisi)
    private array $activeStatuses = [
        'DRAF',
        'DIHANTAR',
        'MENUNGGU SEMAKAN',
        'DIPULANGKAN',
        'DALAM SEMAKAN',
        'UNTUK KELULUSAN',
        'PERLU MAKLUMAT',
        'TANGGUH',
    ];

    public function mount(): void
    {
        $staffId = Auth::user()?->staff_id;

        $this->applications = PromotionApplication::query()
            ->where('staff_id', $staffId)
            ->latest()
            ->get()
            ->toArray();

        $hasActive = PromotionApplication::query()
            ->where('staff_id', $staffId)
            ->whereIn('status', $this->activeStatuses)
            ->where(function ($q) {
                // Jika ada is_active, ia boleh diguna sebagai tambahan
                $q->whereNull('is_active')->orWhere('is_active', 1);
            })
            ->exists();

        $this->canCreate = !$hasActive;
    }

    public function render()
    {
        return view('livewire.app.permohonan.index', [
            'canCreate' => $this->canCreate,
            'applications' => $this->applications,
        ])->layout('layouts.app');
    }
}
