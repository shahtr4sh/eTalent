<?php

namespace App\Filament\Resources\PromotionApplicationResource\Pages;

use App\Filament\Resources\PromotionApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPromotionApplications extends ListRecords
{
    protected static string $resource = PromotionApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
