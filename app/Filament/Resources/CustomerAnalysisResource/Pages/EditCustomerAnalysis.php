<?php

namespace App\Filament\Resources\CustomerAnalysisResource\Pages;

use App\Filament\Resources\CustomerAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerAnalysis extends EditRecord
{
    protected static string $resource = CustomerAnalysisResource::class;

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
