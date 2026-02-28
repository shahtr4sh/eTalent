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
        // Pastikan user_id sentiasa diisi
        $data['user_id'] = Auth::id();

        // Default status bila create (draf)
        $data['status'] = $data['status'] ?? 'DRAF';

        // Jana reference_no jika belum ada
        $data['reference_no'] = $data['reference_no'] ?? $this->generateReferenceNo();

        // Handle upload docs seperti sedia ada
        $docFields = [
            'doc_cv'              => 'CV',
            'doc_publications'    => 'PUBLICATIONS',
            'doc_teaching_project'=> 'TEACHING_PROJECT',
            'doc_certificates'    => 'CERTIFICATES',
            'doc_support_letter'  => 'SUPPORT_LETTER',
            'doc_performance'     => 'PERFORMANCE',
            'doc_other'           => 'OTHER',
        ];

        foreach ($docFields as $field => $code) {
            if (!empty($data[$field])) {
                $this->uploadedDocs[$code] = $data[$field];
            }
            unset($data[$field]);
        }

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
