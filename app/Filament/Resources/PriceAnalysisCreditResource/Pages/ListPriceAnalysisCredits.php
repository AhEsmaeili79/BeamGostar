<?php

namespace App\Filament\Resources\PriceAnalysisCreditResource\Pages;

use App\Filament\Resources\PriceAnalysisCreditResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPriceAnalysisCredits extends ListRecords
{
    protected static string $resource = PriceAnalysisCreditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
