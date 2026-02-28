<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionApplicationResource\Pages;
use App\Models\PromotionApplication;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PromotionApplicationResource extends Resource
{
    protected static ?string $model = PromotionApplication::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Dokumen Permohonan (PDF sahaja)')
                ->schema([
                    FileUpload::make('doc_cv')
                        ->label('CV/Resume Terkini (Wajib)')
                        ->required()
                        ->acceptedFileTypes(['application/pdf'])
                        ->disk('public')
                        ->directory('promotion-applications'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->label('ID'),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromotionApplications::route('/'),
            'create' => Pages\CreatePromotionApplication::route('/create'),
            'edit' => Pages\EditPromotionApplication::route('/{record}/edit'),
        ];
    }
}
