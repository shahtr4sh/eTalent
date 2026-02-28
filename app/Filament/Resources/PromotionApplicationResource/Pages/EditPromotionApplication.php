<?php

namespace App\Filament\Resources\PromotionApplicationResource\Pages;

use App\Filament\Resources\PromotionApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EditPromotionApplication extends EditRecord
{
    protected static string $resource = PromotionApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('submitApplication')
                ->label('Hantar Permohonan')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'DRAF')
                ->action(function () {
                    try {
                        DB::transaction(function () {
                            $app = $this->record->fresh(['documents']);

                            // 1) Validasi field wajib (required)
                            $requiredFields = [
                                'jenis_kenaikan',
                                'jawatan_dipohon',
                                'gred_dipohon',
                            ];

                            foreach ($requiredFields as $field) {
                                if (blank($app->{$field})) {
                                    throw new \RuntimeException("Sila isi medan input wajib dengan lengkap: {$field}");
                                }
                            }

                            // 2) Validasi dokumen wajib
                            $requiredDocs = array_keys(config('promotion.required_documents'));
                            $uploadedDocs = $app->documents->pluck('doc_code')->unique()->values()->all();

                            $missing = array_values(array_diff($requiredDocs, $uploadedDocs));
                            if (!empty($missing)) {
                                throw new \RuntimeException('Dokumen wajib belum dimuat naik: ' . implode(', ', $missing));
                            }

                            // 3) Jana rujukan
                            $ref = \App\Services\ReferenceService::nextPromotionRef();

                            // 4) Update status
                            $app->update([
                                'reference_no' => $ref,
                                'status' => 'MENUNGGU_SEMAKAN',
                                'submitted_at' => now(),
                            ]);

                            // 5) Notifikasi urusetia akan dibuat kemudian (fasa seterusnya)
                        });

                        Notification::make()
                            ->title('Permohonan berjaya dihantar')
                            ->body('Status telah ditetapkan kepada MENUNGGU_SEMAKAN.')
                            ->success()
                            ->send();

                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Permohonan gagal dihantar')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
