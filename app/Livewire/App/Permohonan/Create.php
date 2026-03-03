<?php

namespace App\Livewire\App\Permohonan;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\PromotionApplication;
use App\Models\ApplicationDocument;

class Create extends Component
{
    use WithFileUploads;

    public $doc_cv;
    public $doc_publications;
    public $doc_teaching_project;
    public $doc_certificates;
    public $doc_support_letter;
    public $doc_performance;
    public $doc_other;

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

    public function save()
    {
        $staffId = Auth::user()?->staff_id;

        if (blank($staffId)) {
            $this->addError('general', 'Akaun ini belum mempunyai Staff ID.');
            return;
        }

        // Rule: 1 staf tidak boleh buat permohonan baharu jika ada permohonan aktif
        $hasActive = PromotionApplication::query()
            ->where('staff_id', $staffId)
            ->whereIn('status', $this->activeStatuses)
            ->where(function ($q) {
                $q->whereNull('is_active')->orWhere('is_active', 1);
            })
            ->exists();

        if ($hasActive) {
            $this->addError('general', 'Anda masih mempunyai permohonan aktif. Permohonan baharu tidak dibenarkan.');
            return;
        }

        $this->validate([
            'doc_cv' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
            'doc_publications' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
            'doc_teaching_project' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
            'doc_certificates' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
            'doc_support_letter' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
            'doc_performance' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
            'doc_other' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
        ]);

        $app = PromotionApplication::create([
            'staff_id'      => $staffId,
            'status'        => 'DRAF',
            'is_active'     => 1,
            'reference_no'  => $this->generateReferenceNo(),
        ]);

        $map = [
            'CV'               => $this->doc_cv,
            'PUBLICATIONS'     => $this->doc_publications,
            'TEACHING_PROJECT' => $this->doc_teaching_project,
            'CERTIFICATES'     => $this->doc_certificates,
            'SUPPORT_LETTER'   => $this->doc_support_letter,
            'PERFORMANCE'      => $this->doc_performance,
            'OTHER'            => $this->doc_other,
        ];

        foreach ($map as $code => $file) {
            if ($file) {
                $path = $file->store("promotion-applications/{$app->id}", 'public');

                ApplicationDocument::create([
                    'promotion_application_id' => $app->id,
                    'doc_code'  => $code,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('app.permohonan.index');
    }

    private function generateReferenceNo(): string
    {
        return 'PA-' . now()->format('Ymd') . '-' . strtoupper(Str::ulid());
    }

    public function render()
    {
        return view('livewire.app.permohonan.create')
            ->layout('layouts.app');
    }
}
