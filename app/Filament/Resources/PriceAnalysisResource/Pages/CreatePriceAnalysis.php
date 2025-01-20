<?php

namespace App\Filament\Resources\PriceAnalysisResource\Pages;

use App\Filament\Resources\PriceAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePriceAnalysis extends CreateRecord
{
    protected static string $resource = PriceAnalysisResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
