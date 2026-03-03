<?php

namespace App\Filament\Resources\PromotionApplicationResource\Pages;

use App\Filament\Resources\PromotionApplicationResource;
use App\Models\ApplicationDocument;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CreatePromotionApplication extends CreateRecord
{
    protected static string $resource = PromotionApplicationResource::class;

    protected array $uploadedDocs = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        // Wajib ada staff_id sebab DB staff_id-based
        $staffId = $user?->staff_id;

        if (blank($staffId)) {
            throw new \RuntimeException('Akaun ini belum mempunyai Staff ID. Sila tetapkan staff_id pada users terlebih dahulu.');
        }

        $data['staff_id'] = $staffId;

        // Default status bila create (draf)
        $data['status'] = $data['status'] ?? 'DRAF';

        if (!array_key_exists('is_active', $data)) {
            $data['is_active'] = 1;
        }

        // Jana reference_no jika belum ada
        $data['reference_no'] = $data['reference_no'] ?? $this->generateReferenceNo();

        // Handle upload docs
        $docFields = [
            'doc_cv'               => 'CV',
            'doc_publications'     => 'PUBLICATIONS',
            'doc_teaching_project' => 'TEACHING_PROJECT',
            'doc_certificates'     => 'CERTIFICATES',
            'doc_support_letter'   => 'SUPPORT_LETTER',
            'doc_performance'      => 'PERFORMANCE',
            'doc_other'            => 'OTHER',
        ];

        foreach ($docFields as $field => $code) {
            if (!empty($data[$field])) {
                $this->uploadedDocs[$code] = $data[$field];
            }
            unset($data[$field]);
        }

        // Pastikan tiada lagi user_id dihantar ke query insert
        unset($data['user_id']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $promotionApplicationId = $this->record->id;

        foreach ($this->uploadedDocs as $docCode => $filePath) {
            ApplicationDocument::create([
                'promotion_application_id' => $promotionApplicationId,
                'doc_code' => $docCode,
                'file_path' => $filePath,
            ]);
        }
    }

    private function generateReferenceNo(): string
    {
        // Contoh: PA-20260226-01KJD1C055J5E11NTXB5DYAFN6
        return 'PA-' . now()->format('Ymd') . '-' . strtoupper(Str::ulid());
    }
}
