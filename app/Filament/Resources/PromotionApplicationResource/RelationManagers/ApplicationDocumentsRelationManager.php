<?php

namespace App\Filament\Resources\PromotionApplicationResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ApplicationDocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Schema $schema): Schema
    {
        // Dokumen pilihan (ikut jadual SRS anda)
        // Anda boleh tambah/ubah ikut SRS
        $docOptions = [
            'D-01' => 'CV/Resume terkini (Wajib)',
            'D-02' => 'Senarai penerbitan (Mengikut polisi)',
            'D-03' => 'Bukti pengajaran/projek (Mengikut polisi)',
            'D-04' => 'Sijil kursus/latihan (Tidak wajib)',
            'D-05' => 'Surat sokongan Ketua Jabatan (Wajib)',
            'D-06' => 'Dokumen prestasi (PPK/penilaian) (Mengikut polisi)',
            'D-07' => 'Dokumen lain (Tidak wajib)',
        ];

        return $schema->schema([
            Select::make('doc_code')
                ->label('Kod Dokumen')
                ->options($docOptions)
                ->searchable()
                ->required()
                ->reactive(),

            // File upload ikut polisi:
            // - PDF utama, JPG/PNG jika diperlukan
            // - Saiz ikut polisi (letak dalam config)
            FileUpload::make('file_path')
                ->label('Muat Naik Dokumen')
                ->disk('public')
                ->directory('promotion-documents')
                ->preserveFilenames() // kita akan override dengan nama automatik di bawah
                ->acceptedFileTypes([
                    'application/pdf',
                    'image/jpeg',
                    'image/png',
                ])
                ->maxSize(config('promotion.max_upload_kb', 5120)) // default 5MB jika config tiada
                ->getUploadedFileNameForStorageUsing(function ($file, $get) {
                    // Penamaan automatik: {Application_ID}_{KodDokumen}.{ext}
                    $appId = $this->getOwnerRecord()->getKey();
                    $docCode = (string) $get('doc_code');
                    $ext = strtolower($file->getClientOriginalExtension() ?: 'pdf');

                    // sanitize docCode
                    $docCode = Str::upper(str_replace(' ', '', $docCode));

                    return "{$appId}_{$docCode}.{$ext}";
                })
                ->required(),

            // optional: simpan original_name untuk rujukan (jika column wujud)
            TextInput::make('original_name')
                ->label('Nama Asal Fail')
                ->disabled()
                ->dehydrated(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('doc_code')
            ->columns([
                Tables\Columns\TextColumn::make('doc_code')->label('Kod')->sortable(),
                Tables\Columns\TextColumn::make('file_path')->label('Fail')->limit(60),
                Tables\Columns\TextColumn::make('created_at')->label('Tarikh')->dateTime(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Muat Turun')
                    ->url(fn ($record) => \Storage::disk('public')->url($record->file_path))
                    ->openUrlInNewTab(),
            ]);
    }
}
