<?php

namespace App\Filament\Resources\PriceAnalysisCreditResource\Pages;

use App\Filament\Resources\PriceAnalysisCreditResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPriceAnalysisCredit extends EditRecord
{
    protected static string $resource = PriceAnalysisCreditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
