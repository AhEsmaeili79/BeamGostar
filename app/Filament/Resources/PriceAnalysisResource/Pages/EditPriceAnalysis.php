<?php

namespace App\Filament\Resources\PriceAnalysisResource\Pages;

use App\Filament\Resources\PriceAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPriceAnalysis extends EditRecord
{
    protected static string $resource = PriceAnalysisResource::class;

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
