<?php

namespace App\Filament\Resources\PriceAnalysisResource\Pages;

use App\Filament\Resources\PriceAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPriceAnalyses extends ListRecords
{
    protected static string $resource = PriceAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
