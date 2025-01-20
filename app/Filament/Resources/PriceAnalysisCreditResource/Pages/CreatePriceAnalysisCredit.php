<?php

namespace App\Filament\Resources\PriceAnalysisCreditResource\Pages;

use App\Filament\Resources\PriceAnalysisCreditResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePriceAnalysisCredit extends CreateRecord
{
    protected static string $resource = PriceAnalysisCreditResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
