<?php

namespace App\Filament\Resources\AnalysisDiscountResource\Pages;

use App\Filament\Resources\AnalysisDiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnalysisDiscounts extends ListRecords
{
    protected static string $resource = AnalysisDiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
