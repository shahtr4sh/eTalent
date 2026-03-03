<?php

namespace App\Livewire\App\Permohonan;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Pemohon;
use App\Models\SelectJawatan;
use App\Models\PromotionApplication;
use App\Models\ApplicationDocument;

class Create extends Component
{
    use WithFileUploads;

    // 1) Jawatan dipohon
    public ?string $selected_id_daftar = null;

    // 2) Borang kenaikan pangkat (PDF)
    public $borang_pdf;

    // 3) Dokumen wajib
    public $cv_resume;
    public $dok_sokongan;
    public $penilaian_prestasi;

    // Untuk dropdown
    public array $jawatanOptions = [];

    public function mount(): void
    {
        $staffId = Auth::user()?->staff_id;

        // Load jawatan ingin dipohon (dropdown)
        $this->jawatanOptions = SelectJawatan::query()
            ->orderBy('id_daftar')
            ->get()
            ->map(fn ($j) => [
                'value' => (string) $j->id_daftar,
                'label' => trim(($j->kodJawatan ?? '') . ' - ' . ($j->nama_jawatan ?? ''). ' (' . ($j->gredJawatan ?? '') . ')' ),
            ])
            ->toArray();
    }

    protected function rules(): array
    {
        return [
            'selected_id_daftar' => ['required', 'exists:select_jawatan,id_daftar'],

            'borang_pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'], // 10MB
            'cv_resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'dok_sokongan' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'penilaian_prestasi' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }

    protected array $messages = [
        'selected_id_daftar.required' => 'Sila pilih jawatan yang ingin dipohon.',

        'borang_pdf.required' => 'Sila muat naik borang kenaikan pangkat (PDF).',
        'cv_resume.required' => 'Sila muat naik CV/Resume.',
        'dok_sokongan.required' => 'Sila muat naik dokumen sokongan.',
        'penilaian_prestasi.required' => 'Sila muat naik dokumen penilaian prestasi.',
    ];

    public function submit()
    {
        $this->validate();

        $user = Auth::user();
        $staffId = $user?->staff_id;

        if (blank($staffId)) {
            $this->dispatch('notify', type: 'error', message: 'Staff ID tiada pada akaun ini.');
            return;
        }

        // Optional: block jika masih ada permohonan aktif
        $hasActive = PromotionApplication::query()
            ->where('staff_id', $staffId)
            ->whereIn('status', ['DRAF','DIHANTAR','MENUNGGU SEMAKAN','DIPULANGKAN','DALAM SEMAKAN','UNTUK KELULUSAN','PERLU MAKLUMAT','TANGGUH'])
            ->exists();

        if ($hasActive) {
            $this->dispatch('notify', type: 'error', message: 'Anda masih mempunyai permohonan aktif. Sila selesaikan permohonan sedia ada.');
            return;
        }

        // Pastikan jawatan wujud
        $jawatanRef = SelectJawatan::query()
            ->where('id_daftar', $this->selected_id_daftar)
            ->first();

        if (! $jawatanRef) {
            $this->dispatch('notify', type: 'error', message: 'Jawatan dipilih tidak sah.');
            return;
        }

        DB::beginTransaction();

        try {
            // Create application
            $app = PromotionApplication::create([
                'staff_id'      => $staffId,
                'reference_no'  => 'PA-' . now()->format('Ymd') . '-' . strtoupper(Str::ulid()),
                'status'        => 'DIHANTAR',  // atau 'DRAF' jika anda mahu draft dulu
                'is_active'     => 1,

                // Jika belum ada field, anda boleh simpan dalam metadata JSON (jika ada):
                'metadata' => json_encode([
                    'id_daftar'   => $jawatanRef->id_daftar,
                    'kod_jawatan' => $jawatanRef->kodJawatan ?? null,
                    'nama_jawatan'=> $jawatanRef->nama_jawatan ?? null,
                    'gred_jawatan'=> $jawatanRef->gredJawatan ?? null,
                ]),
            ]);

            // Upload files
            $basePath = "promotion_applications/{$app->id}";

            $borangPath = $this->borang_pdf->store($basePath, 'public');
            $cvPath     = $this->cv_resume->store($basePath, 'public');
            $sokPath    = $this->dok_sokongan->store($basePath, 'public');
            $prestasiPath = $this->penilaian_prestasi->store($basePath, 'public');

            // Save to application_documents
            $staff_id = auth()->user()->staff_id;
            ApplicationDocument::insert([
                [
                    'promotion_application_id' => $app->id,
                    'doc_code' => 'BORANG',
                    'file_path' => $borangPath,
                    'uploaded_by_staff_id' => $staff_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'promotion_application_id' => $app->id,
                    'doc_code' => 'CV',
                    'file_path' => $cvPath,
                    'uploaded_by_staff_id' => $staff_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'promotion_application_id' => $app->id,
                    'doc_code' => 'SUPPORT',
                    'file_path' => $sokPath,
                    'uploaded_by_staff_id' => $staff_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'promotion_application_id' => $app->id,
                    'doc_code' => 'PERFORMANCE',
                    'file_path' => $prestasiPath,
                    'uploaded_by_staff_id' => $staff_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            DB::commit();

            $this->dispatch('notify', type: 'success', message: 'Permohonan berjaya dihantar.');

            return redirect()->route('app.permohonan.index');

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatch('notify', type: 'error', message: 'Permohonan gagal dihantar. Sila cuba semula.');
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.app.permohonan.create')
            ->layout('layouts.app');
    }
}
